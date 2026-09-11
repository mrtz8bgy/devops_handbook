<?php
// ============================================================
// DevOps Handbook v3 — موتور چت‌بات حرفه‌ای
// نرمال‌سازی فارسی، مترادف‌ها، تشخیص نیت، امتیازدهی، فیدبک
// ============================================================
if (defined('DH_CHATBOT')) return;
define('DH_CHATBOT', true);

/** نام‌های فارسی دسته‌بندی‌ها */
function cat_fa_aliases() {
    return [
        'داکر' => 'Docker', 'لینوکس' => 'Linux', 'گیت' => 'Git',
        'کوبرنتیز' => 'Kubernetes', 'کوبرنیتز' => 'Kubernetes', 'کیوبرنتیز' => 'Kubernetes',
        'شبکه' => 'Network', 'انسیبل' => 'Ansible', 'جنکینز' => 'Jenkins',
        'پایتون' => 'Python', 'پستگرس' => 'PostgreSQL', 'مونگو' => 'MongoDB',
        'ردیس' => 'Redis', 'انجین ایکس' => 'Nginx', 'انجینکس' => 'Nginx',
        'ترافورم' => 'Terraform', 'دیتابیس' => 'Database', 'پایگاه داده' => 'Database',
        'گیت لب' => 'GitLab', 'گیتلب' => 'GitLab', 'جیرا' => 'Jira',
        'نکسوس' => 'Nexus', 'اس اس ال' => 'SSL',
    ];
}

/** جدول مترادف‌ها (کش داخل ریکوئست) */
function synonym_map($pdo) {
    static $map = null;
    if ($map === null) {
        $map = [];
        try {
            foreach ($pdo->query("SELECT word, synonym_for FROM chatbot_synonyms")->fetchAll(PDO::FETCH_ASSOC) as $r) {
                $map[fa_normalize($r['word'])] = fa_normalize($r['synonym_for']);
            }
        } catch (Exception $e) {}
    }
    return $map;
}

function apply_synonyms($pdo, $text) {
    $map = synonym_map($pdo);
    if (!$map) return $text;
    $words = preg_split('/\s+/u', fa_normalize($text));
    foreach ($words as &$w) {
        if (isset($map[$w])) $w = $map[$w];
    }
    return implode(' ', $words);
}

/** پیشنهادهای پیگیری بر اساس دسته */
function followup_suggestions($category) {
    $m = [
        'Docker' => ['لاگ داکر', 'اجرای کانتینر', 'دستورات داکر'],
        'Kubernetes' => ['لاگ پاد', 'دستورات کوبرنتیز', 'اسکیل'],
        'Linux' => ['فضای دیسک', 'پورت باز', 'دستورات لینوکس'],
        'Git' => ['کامیت', 'برنچ', 'دستورات گیت'],
        'Network' => ['تست پورت', 'پینگ', 'دستورات شبکه'],
        'PostgreSQL' => ['بکاپ postgres', 'اتصال psql', 'دستورات دیتابیس'],
        'MongoDB' => ['بکاپ مونگو', 'اتصال مونگو', 'دستورات دیتابیس'],
        'Redis' => ['اتصال ردیس', 'بکاپ ردیس', 'دستورات دیتابیس'],
        'Nginx' => ['تست nginx', 'لاگ nginx', 'ssl رایگان'],
    ];
    return $m[$category] ?? ['دسته‌بندی‌ها', 'راهنما', 'لاگ داکر'];
}

function chat_categories_list($pdo) {
    try {
        return $pdo->query(
            "SELECT c.category AS name, COUNT(cmd.id) AS cnt FROM categories c
             LEFT JOIN commands cmd ON cmd.category = c.category
             GROUP BY c.category ORDER BY c.category")->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) { return []; }
}

/**
 * پاسخ‌گویی اصلی چت‌بات
 * خروجی: ['answer'=>html, 'commands'=>[], 'suggestions'=>[], 'qa_id'=>?, 'intent'=>..., 'count'=>n]
 */
function chatbot_answer($pdo, $question, $opts = []) {
    $q = trim((string)$question);
    $uid = $opts['user_id'] ?? null;
    if ($q === '') {
        return ['answer' => 'پیامت خالی بود! 😅 یه دستور یا سؤال بنویس، مثلاً «لاگ داکر» یا «بکاپ mysql».',
            'commands' => [], 'suggestions' => ['لاگ داکر', 'دسته‌بندی‌ها', 'راهنما'], 'qa_id' => null, 'intent' => 'empty', 'count' => 0];
    }
    $n = fa_normalize($q);
    $tokens = fa_tokens($q, 2);

    // ---------- نیت‌های smalltalk ----------
    $has = fn($words) => (bool)preg_match('/(' . implode('|', array_map(fn($w) => preg_quote($w, '/'), $words)) . ')/u', $n);
    if ($has(['سلام', 'درود', 'سلاام', 'hello', 'hi', 'salam']) && mb_strlen($n, 'UTF-8') < 30 && !$has(['دستور', 'لاگ', 'بکاپ', 'نصب'])) {
        $qa = qa_lookup($pdo, 'سلام');
        $text = $qa ? nl2br(esc($qa['answer'])) : '👋 سلام عزیز! من دستیار DevOps هستم. بپرس چی بلدی؟ مثلاً «دستورات داکر» یا «لاگ لینوکس».';
        return ['answer' => $text, 'commands' => [], 'suggestions' => ['راهنما', 'دسته‌بندی‌ها', 'لاگ داکر'],
            'qa_id' => $qa['id'] ?? null, 'intent' => 'greeting', 'count' => 0];
    }
    if ($has(['خداحافظ', 'خدافظ', 'فعلا', 'بای', 'bye', 'goodbye'])) {
        return ['answer' => 'فعلاً! 👋 هر وقت دستوری یادت رفت برگرد، من اینجام.', 'commands' => [],
            'suggestions' => ['سلام', 'راهنما'], 'qa_id' => null, 'intent' => 'bye', 'count' => 0];
    }
    if ($has(['ممنون', 'مرسی', 'متشکر', 'مچکر', 'تشکر', 'thanks', 'thank'])) {
        $r = ['قابلی نداشت! 🙏 سؤال دیگه‌ای داری در خدمتم.', 'خواهش می‌کنم! 😊 دستور بعدی چیه؟'];
        return ['answer' => $r[array_rand($r)], 'commands' => [], 'suggestions' => ['راهنما', 'دسته‌بندی‌ها'],
            'qa_id' => null, 'intent' => 'thanks', 'count' => 0];
    }
    if ($has(['راهنما', 'کمک', 'help', 'چیکار', 'چکار', 'میتونی', 'توانایی'])) {
        return ['answer' => '📖 <b>راهنما:</b><br>• اسم دستور یا کارت رو بنویس: «لاگ داکر»، «بکاپ mysql»، «پورت باز»<br>• فارسی یا انگلیسی — هر دو رو می‌فهمم<br>• بنویس «دسته‌بندی‌ها» تا همه موضوعات رو ببینی<br>• زیر هر پاسخ می‌تونی 👍/👎 بدی تا باهوش‌تر بشم',
            'commands' => [], 'suggestions' => ['دسته‌بندی‌ها', 'لاگ داکر', 'بکاپ mysql'], 'qa_id' => null, 'intent' => 'help', 'count' => 0];
    }
    if ($has(['دسته بندی', 'کتگوری', 'category', 'categories', 'موضوعات', 'لیست دسته'])) {
        $cats = chat_categories_list($pdo);
        $items = '';
        foreach ($cats as $c) {
            $items .= '<button class="chat-cat" data-q="دستورات ' . esc($c['name']) . '">' . category_icon($c['name']) . ' <b>' . esc($c['name']) . '</b><span>' . fa_digits($c['cnt']) . '</span></button>';
        }
        return ['answer' => '📚 <b>دسته‌بندی‌ها:</b><div class="chat-cats">' . $items . '</div>',
            'commands' => [], 'suggestions' => ['راهنما', 'لاگ داکر', 'تست پورت'], 'qa_id' => null, 'intent' => 'categories', 'count' => count($cats)];
    }
    if ($has(['چند دستور', 'تعداد دستور', 'آمار', 'امار'])) {
        try {
            $e = $pdo->query("SELECT COUNT(*) FROM commands")->fetchColumn();
            $c = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
            return ['answer' => '📊 الان <b>' . fa_digits($e) . '</b> دستور در <b>' . fa_digits($c) . '</b> دسته‌بندی داریم و هر روز بیشتر می‌شه!',
                'commands' => [], 'suggestions' => ['دسته‌بندی‌ها', 'راهنما'], 'qa_id' => null, 'intent' => 'stats', 'count' => 0];
        } catch (Exception $e) {}
    }

    // ---------- درخواست صریح دسته («دستورات داکر») مقدم بر QA است ----------
    if (preg_match('/(دستورات|دستور|لیست|همه)\s+(.+)/u', $n, $mm)) {
        $early = match_category_name($pdo, trim($mm[2]));
        if ($early) {
            $st = $pdo->prepare("SELECT id, command, description, category FROM commands WHERE category = ? ORDER BY command LIMIT 30");
            $st->execute([$early]);
            $erows = $st->fetchAll(PDO::FETCH_ASSOC);
            return ['answer' => category_icon($early) . ' همه دستورات <b>' . esc($early) . '</b> (' . fa_digits(count($erows)) . ' دستور):',
                'commands' => $erows, 'suggestions' => followup_suggestions($early),
                'qa_id' => null, 'intent' => 'category', 'count' => count($erows)];
        }
    }

    // ---------- تطابق QA ----------
    $best = qa_best_match($pdo, $q, $tokens);
    if ($best && $best['score'] >= 120) {
        $pdo->prepare("UPDATE chatbot_qa SET usage_count = usage_count + 1 WHERE id = ?")->execute([$best['id']]);
        return ['answer' => nl2br(esc($best['answer'])), 'commands' => [],
            'suggestions' => followup_suggestions($best['category'] ?? ''), 'qa_id' => (int)$best['id'],
            'intent' => 'qa', 'count' => 1];
    }

    // ---------- تشخیص دسته‌بندی ----------
    $expanded = apply_synonyms($pdo, $q);
    $matched = detect_category($pdo, $n . ' ' . fa_normalize($expanded));
    if ($matched) {
        $st = $pdo->prepare("SELECT id, command, description, category FROM commands WHERE category = ? ORDER BY command LIMIT 30");
        $st->execute([$matched]);
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);
        $icon = category_icon($matched);
        $text = "{$icon} همه دستورات <b>" . esc($matched) . "</b> (" . fa_digits(count($rows)) . " دستور):";
        return ['answer' => $text, 'commands' => $rows, 'suggestions' => followup_suggestions($matched),
            'qa_id' => null, 'intent' => 'category', 'count' => count($rows)];
    }

    // ---------- جستجوی دستورات ----
    $res = pro_search($pdo, $expanded !== $q ? $expanded . ' ' . $q : $q, ['per' => 8, 'log' => false]);
    if ($res['total'] > 0) {
        $intros = ['پیداش کردم! 🎯', 'باشه، بذار نشونت بدم 👇', 'این همون چیزیه که دنبالشی 👇'];
        $text = $intros[array_rand($intros)] . ' <b>' . fa_digits($res['total']) . '</b> نتیجه برای «' . esc($q) . '»:';
        $first_cat = $res['results'][0]['category'] ?? '';
        return ['answer' => $text, 'commands' => $res['results'], 'suggestions' => followup_suggestions($first_cat),
            'qa_id' => null, 'intent' => 'search', 'count' => $res['total']];
    }

    // ---------- بی‌جواب: ثبت برای یادگیری ----
    log_unknown($pdo, $q);
    return ['answer' => '🤔 چیزی پیدا نکردم! چند پیشنهاد:<br>• کوتاه‌تر بنویس (مثلاً «لاگ» )<br>• انگلیسی امتحان کن (مثلاً <code dir="ltr">docker logs</code>)<br>• از دسته‌بندی‌ها مرور کن<br><br>✅ سؤالت ثبت شد تا مدیر جوابش رو اضافه کنه.',
        'commands' => [], 'suggestions' => ['دسته‌بندی‌ها', 'راهنما', 'لاگ داکر'], 'qa_id' => null, 'intent' => 'unknown', 'count' => 0];
}

/** جستجوی دقیق یک سؤال در QA */
function qa_lookup($pdo, $question) {
    try {
        $st = $pdo->prepare("SELECT * FROM chatbot_qa WHERE question = ? LIMIT 1");
        $st->execute([$question]);
        $r = $st->fetch(PDO::FETCH_ASSOC);
        if ($r) {
            $pdo->prepare("UPDATE chatbot_qa SET usage_count = usage_count + 1 WHERE id = ?")->execute([$r['id']]);
            return $r;
        }
    } catch (Exception $e) {}
    return null;
}

/** بهترین تطابق QA با امتیازدهی */
function qa_best_match($pdo, $q, $tokens) {
    $cands = [];
    try {
        // کاندیداها: LIKE روی سؤال/کلیدواژه‌ها
        $like = '%' . like_escape($q) . '%';
        $st = $pdo->prepare("SELECT * FROM chatbot_qa WHERE question LIKE ? OR keywords LIKE ? OR tags LIKE ? LIMIT 60");
        $st->execute([$like, $like, $like]);
        $cands = $st->fetchAll(PDO::FETCH_ASSOC);
        // + کاندیداهای FULLTEXT
        $bq = fts_boolean($tokens);
        if ($bq) {
            try {
                $st = $pdo->prepare("SELECT *, MATCH(question, keywords, answer) AGAINST (? IN BOOLEAN MODE) AS rel
                    FROM chatbot_qa WHERE MATCH(question, keywords, answer) AGAINST (? IN BOOLEAN MODE) LIMIT 60");
                $st->execute([$bq, $bq]);
                foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) {
                    $cands['ft' . $r['id']] = $r;
                }
            } catch (Exception $e) {}
        }
    } catch (Exception $e) { return null; }

    $nq = fa_normalize($q);
    $best = null; $best_score = 0;
    foreach ($cands as $r) {
        $s = 0;
        $nques = fa_normalize($r['question'] ?? '');
        $nkey = fa_normalize(($r['keywords'] ?? '') . ' ' . ($r['tags'] ?? ''));
        if ($nques !== '' && $nques === $nq) $s += 1000;
        if ($nques !== '' && mb_strpos($nques, $nq) !== false) $s += 300;
        $hit_q = 0;
        foreach ($tokens as $t) {
            if ($t !== '' && mb_strpos($nques, $t) !== false) { $s += 90; $hit_q++; }
            if ($t !== '' && mb_strpos($nkey, $t) !== false) $s += 70;
        }
        if ($tokens && $hit_q === count($tokens)) $s += 200;
        $s += (float)($r['rel'] ?? 0) * 8;
        $s *= (1 + (int)($r['weight'] ?? 1) * 0.05);
        $s += min((int)($r['usage_count'] ?? 0), 50);
        if ($s > $best_score) { $best_score = $s; $best = $r; }
    }
    if ($best) $best['score'] = $best_score;
    return $best;
}

/** تشخیص دسته از روی متن */
function detect_category($pdo, $norm_text) {
    // الگوهای «دستورات X» / «لیست X»
    if (preg_match('/(دستورات|دستور|لیست|همه)\s+(.+)/u', $norm_text, $m)) {
        $target = trim($m[2]);
        $found = match_category_name($pdo, $target);
        if ($found) return $found;
    }
    foreach (cat_fa_aliases() as $fa => $en) {
        if (mb_strpos($norm_text, fa_normalize($fa)) !== false) return $en;
    }
    try {
        $cats = $pdo->query("SELECT category FROM categories")->fetchAll(PDO::FETCH_COLUMN);
        foreach ($cats as $c) {
            if (mb_strpos($norm_text, mb_strtolower($c, 'UTF-8')) !== false) return $c;
        }
    } catch (Exception $e) {}
    return null;
}

function match_category_name($pdo, $target) {
    $aliases = cat_fa_aliases();
    if (isset($aliases[$target])) return $aliases[$target];
    try {
        $cats = $pdo->query("SELECT category FROM categories")->fetchAll(PDO::FETCH_COLUMN);
        foreach ($cats as $c) {
            if (mb_strtolower($c, 'UTF-8') === mb_strtolower($target, 'UTF-8')) return $c;
            if (mb_strpos(mb_strtolower($c, 'UTF-8'), mb_strtolower($target, 'UTF-8')) !== false && mb_strlen($target, 'UTF-8') >= 3) return $c;
        }
    } catch (Exception $e) {}
    return null;
}

/** ثبت سؤال بی‌جواب */
function log_unknown($pdo, $q) {
    try {
        $q = mb_substr(trim($q), 0, 500);
        if ($q === '') return;
        $st = $pdo->prepare("SELECT id, asked_count FROM chatbot_unknown_questions WHERE question = ?");
        $st->execute([$q]);
        $ex = $st->fetch(PDO::FETCH_ASSOC);
        if ($ex) {
            $pdo->prepare("UPDATE chatbot_unknown_questions SET asked_count = asked_count + 1, last_asked = NOW() WHERE id = ?")->execute([$ex['id']]);
        } else {
            $pdo->prepare("INSERT INTO chatbot_unknown_questions (question) VALUES (?)")->execute([$q]);
        }
    } catch (Exception $e) {}
}

/** ثبت فیدبک */
function save_feedback($pdo, $qa_id, $session_id, $feedback) {
    $allowed = ['good', 'bad', 'excellent', 'needs_improvement'];
    if (!in_array($feedback, $allowed, true)) return false;
    try {
        $pdo->prepare("INSERT INTO chatbot_feedback (qa_id, session_id, feedback) VALUES (?,?,?)")
            ->execute([(int)$qa_id ?: null, mb_substr((string)$session_id, 0, 100), $feedback]);
        return true;
    } catch (Exception $e) { return false; }
}
