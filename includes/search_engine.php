<?php
// ============================================================
// DevOps Handbook v3 — موتور جستجوی حرفه‌ای
// ترکیب FULLTEXT (boolean) + تطابق دقیق/پیشوندی + امتیازدهی
// ============================================================
if (defined('DH_SEARCH')) return;
define('DH_SEARCH', true);

/** escape کاراکترهای خاص LIKE */
function like_escape($s) {
    return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], (string)$s);
}

/** ساخت کوئری امن Boolean برای FULLTEXT */
function fts_boolean($tokens) {
    $parts = [];
    foreach ($tokens as $t) {
        $t = preg_replace('/[+\-><()~*":@]+/u', '', $t);
        if (mb_strlen($t, 'UTF-8') < 3) continue; // کوتاه‌تر از حد ft_min_word_len
        $parts[] = '+' . $t . '*';
    }
    return $parts ? implode(' ', $parts) : null;
}

/**
 * جستجوی حرفه‌ای دستورات
 * $opts: category, page, per, log(bool), user_id
 * خروجی: ['results'=>[], 'total'=>n, 'tokens'=>[], 'page'=>, 'per'=>, 'pages'=>]
 */
function pro_search($pdo, $q, $opts = []) {
    $q = trim((string)$q);
    $cat = $opts['category'] ?? null;
    $page = max(1, (int)($opts['page'] ?? 1));
    $per = min(50, max(5, (int)($opts['per'] ?? 12)));
    $out = ['results' => [], 'total' => 0, 'tokens' => [], 'page' => $page, 'per' => $per, 'pages' => 0];
    if ($q === '') return $out;

    $tokens = fa_tokens($q, 2);
    $out['tokens'] = $tokens;
    $merged = [];

    // ---- مرحله ۱: FULLTEXT ----
    $bq = fts_boolean($tokens);
    if ($bq) {
        try {
            $sql = "SELECT *, MATCH(command, description, keywords) AGAINST (? IN BOOLEAN MODE) AS rel
                    FROM commands WHERE MATCH(command, description, keywords) AGAINST (? IN BOOLEAN MODE)";
            $p = [$bq, $bq];
            if ($cat) { $sql .= " AND category = ?"; $p[] = $cat; }
            $sql .= " LIMIT 200";
            $st = $pdo->prepare($sql);
            $st->execute($p);
            foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) {
                $r['_w'] = (float)$r['rel'] * 10;
                $merged[$r['id']] = $r;
            }
        } catch (Exception $e) { /* ایندکس FULLTEXT نیست — ادامه با LIKE */ }
    }

    // ---- مرحله ۲: تطابق دقیق / پیشوندی / فراگیر ----
    try {
        $le = like_escape($q);
        $sql = "SELECT *, CASE
                    WHEN LOWER(command) = LOWER(?) THEN 1000
                    WHEN command LIKE ? THEN 500
                    WHEN command LIKE ? THEN 300
                    WHEN keywords LIKE ? THEN 150
                    WHEN description LIKE ? THEN 80
                    ELSE 10 END AS w
                FROM commands
                WHERE (command LIKE ? OR description LIKE ? OR keywords LIKE ?)";
        $p = [$q, $le . '%', '%' . $le . '%', '%' . $le . '%', '%' . $le . '%',
              '%' . $le . '%', '%' . $le . '%', '%' . $le . '%'];
        if ($cat) { $sql .= " AND category = ?"; $p[] = $cat; }
        $sql .= " ORDER BY w DESC LIMIT 200";
        $st = $pdo->prepare($sql);
        $st->execute($p);
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) {
            $w = (float)$r['w'];
            if (isset($merged[$r['id']])) $merged[$r['id']]['_w'] += $w;
            else { $r['_w'] = $w; $merged[$r['id']] = $r; }
        }
    } catch (Exception $e) { /* ignore */ }

    // ---- مرحله ۳: فال‌بک «تطابق هر کلمه» اگر هیچ نتیجه‌ای نبود ----
    if (!$merged && count($tokens) > 1) {
        try {
            $conds = []; $p = [];
            foreach ($tokens as $t) {
                $le = '%' . like_escape($t) . '%';
                $conds[] = "(command LIKE ? OR keywords LIKE ? OR description LIKE ?)";
                $p[] = $le; $p[] = $le; $p[] = $le;
            }
            $sql = "SELECT * FROM commands WHERE (" . implode(' OR ', $conds) . ")";
            if ($cat) { $sql .= " AND category = ?"; $p[] = $cat; }
            $sql .= " LIMIT 200";
            $st = $pdo->prepare($sql);
            $st->execute($p);
            foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) {
                $hits = 0;
                $nt = fa_normalize($r['command'] . ' ' . ($r['keywords'] ?? '') . ' ' . $r['description']);
                foreach ($tokens as $t) {
                    if ($t !== '' && mb_strpos($nt, $t) !== false) $hits++;
                }
                if ($hits > 0) { $r['_w'] = 40 * $hits; $merged[$r['id']] = $r; }
            }
        } catch (Exception $e) { /* ignore */ }
    }

    // ---- امتیاز تکمیلی توکن‌ها + مرتب‌سازی ----
    $nq = fa_normalize($q);
    $list = array_values($merged);
    foreach ($list as &$r) {
        $nt = fa_normalize($r['command'] . ' ' . ($r['keywords'] ?? ''));
        if ($nq !== '' && mb_strpos($nt, $nq) !== false) $r['_w'] += 60;
        foreach ($tokens as $t) {
            if ($t !== '' && mb_strpos($nt, $t) !== false) $r['_w'] += 12;
        }
    }
    unset($r);
    usort($list, fn($a, $b) => $b['_w'] <=> $a['_w']);

    $total = count($list);
    $out['total'] = $total;
    $out['pages'] = (int)ceil($total / $per);
    $out['results'] = array_slice($list, ($page - 1) * $per, $per);

    if (!empty($opts['log'])) {
        log_search($pdo, $q, $total, $opts['user_id'] ?? null);
    }
    return $out;
}

/** هایلایت کلمات جستجو */
function highlight($text, $tokens) {
    $e = esc($text);
    foreach ((array)$tokens as $t) {
        $t = trim($t);
        if (mb_strlen($t, 'UTF-8') < 2) continue;
        $et = esc($t);
        $e = preg_replace('/(' . preg_quote($et, '/') . ')/iu', '<mark class="hl">$1</mark>', $e);
    }
    return $e;
}

/** ثبت لاگ جستجو */
function log_search($pdo, $q, $count, $uid = null) {
    try {
        $pdo->prepare("INSERT INTO search_logs (user_id, query, results, created_at) VALUES (?,?,?,NOW())")
            ->execute([$uid, mb_substr($q, 0, 200), (int)$count]);
    } catch (Exception $e) { /* جدول نیست */ }
}

/** پیشنهاد خودکار (autocomplete) */
function suggest_commands($pdo, $q, $limit = 8) {
    $q = trim((string)$q);
    if (mb_strlen($q, 'UTF-8') < 2) return [];
    $le = like_escape($q);
    $st = $pdo->prepare(
        "SELECT id, command, description, category FROM commands
         WHERE command LIKE ? OR description LIKE ? OR keywords LIKE ?
         ORDER BY (command LIKE ?) DESC, LENGTH(command) ASC LIMIT " . (int)$limit);
    $st->execute(['%' . $le . '%', '%' . $le . '%', '%' . $le . '%', $le . '%']);
    return $st->fetchAll(PDO::FETCH_ASSOC);
}
