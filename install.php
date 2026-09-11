<?php
// ============================================================
// DevOps Handbook v3 — نصب و به‌روزرسانی خودکار
// ⚠️ بعد از نصب موفق، این فایل را حذف کنید!
// ============================================================
$steps = [];
$ok_all = true;
function step($title, $ok, $detail = '') {
    global $steps, $ok_all;
    $steps[] = ['t' => $title, 'ok' => $ok, 'd' => $detail];
    if (!$ok) $ok_all = false;
}

// 1) بررسی PHP
step('نسخه PHP: ' . PHP_VERSION, version_compare(PHP_VERSION, '7.4', '>='));
step('اکستنشن pdo_mysql', extension_loaded('pdo_mysql'));
step('اکستنشن mbstring', extension_loaded('mbstring'));

// 2) اتصال دیتابیس
try {
    require_once __DIR__ . '/config.php';
    step('اتصال به دیتابیس', isset($pdo));
} catch (Throwable $e) {
    step('اتصال به دیتابیس', false, 'خطا: ' . $e->getMessage());
    $pdo = null;
}

function run_sql_file($pdo, $file) {
    $sql = file_get_contents($file);
    $sql = str_replace(["\r\n", "\r"], "\n", $sql);
    $stmts = [];
    $buf = '';
    foreach (explode("\n", $sql) as $line) {
        $trim = trim($line);
        if ($trim === '' || str_starts_with($trim, '--') || str_starts_with($trim, '/*') || str_starts_with($trim, '#')) continue;
        $buf .= $line . "\n";
        if (str_ends_with(rtrim($line), ';')) { $stmts[] = $buf; $buf = ''; }
    }
    if (trim($buf) !== '') $stmts[] = $buf;
    $n = 0;
    foreach ($stmts as $s) { $pdo->exec($s); $n++; }
    return $n;
}

if ($pdo) {
    // 3) مایگریشن v3
    try {
        $n = run_sql_file($pdo, __DIR__ . '/db/migration_v3.sql');
        step('مایگریشن v3 (جداول users و search_logs)', true, $n . ' دستور اجرا شد');
    } catch (Throwable $e) {
        step('مایگریشن v3', false, $e->getMessage());
    }

    // 3b) مایگریشن v4 (هوش مصنوعی)
    try {
        $n = run_sql_file($pdo, __DIR__ . '/db/migration_v4.sql');
        step('مایگریشن v4 (پیش‌نویس AI و تنظیمات)', true, $n . ' دستور اجرا شد');
    } catch (Throwable $e) {
        step('مایگریشن v4', false, $e->getMessage());
    }

    // 3c) ستون‌های AI در chatbot_qa
    try {
        $cols = $pdo->query("SHOW COLUMNS FROM chatbot_qa")->fetchAll(PDO::FETCH_COLUMN);
        if (!in_array('source', $cols, true)) $pdo->exec("ALTER TABLE chatbot_qa ADD COLUMN source VARCHAR(16) NOT NULL DEFAULT 'manual'");
        if (!in_array('ai_model', $cols, true)) $pdo->exec("ALTER TABLE chatbot_qa ADD COLUMN ai_model VARCHAR(100) NULL");
        step('ستون‌های AI در chatbot_qa', true, 'بررسی/ساخته شد');
    } catch (Throwable $e) {
        step('ستون‌های AI در chatbot_qa', false, $e->getMessage());
    }

    // 3d) تبدیل جداول قدیمی به utf8mb4 (پشتیبانی ایموجی در پاسخ‌های AI)
    try {
        $tables = ['categories', 'chatbot_conversation_context', 'chatbot_feedback', 'chatbot_qa',
            'chatbot_synonyms', 'chatbot_unknown_questions', 'command_files', 'commands', 'user_questions'];
        $converted = 0;
        foreach ($tables as $t) {
            try {
                $pdo->exec("ALTER TABLE `$t` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $converted++;
            } catch (Throwable $e) { /* جدول نیست یا قبلاً تبدیل شده */
            }
        }
        step('تبدیل جداول به utf8mb4', true, $converted . ' جدول');
    } catch (Throwable $e) {
        step('تبدیل جداول به utf8mb4', false, $e->getMessage());
    }

    // 4) ایندکس FULLTEXT
    try {
        $has = $pdo->query("SHOW INDEX FROM commands WHERE Key_name = 'ft_commands'")->fetch();
        if (!$has) $pdo->exec("ALTER TABLE commands ADD FULLTEXT INDEX ft_commands (command, description, keywords)");
        step('ایندکس جستجوی commands', true, $has ? 'از قبل موجود' : 'ساخته شد');
    } catch (Throwable $e) {
        step('ایندکس جستجوی commands', false, $e->getMessage());
    }
    try {
        $has = $pdo->query("SHOW INDEX FROM chatbot_qa WHERE Key_name = 'ft_qa'")->fetch();
        if (!$has) $pdo->exec("ALTER TABLE chatbot_qa ADD FULLTEXT INDEX ft_qa (question, keywords, answer)");
        step('ایندکس جستجوی chatbot_qa', true, $has ? 'از قبل موجود' : 'ساخته شد');
    } catch (Throwable $e) {
        step('ایندکس جستجوی chatbot_qa', false, $e->getMessage());
    }

    // 5) ایمپورت دیتای آماده (اختیاری)
    try {
        $cmds = (int)$pdo->query("SELECT COUNT(*) FROM commands")->fetchColumn();
    } catch (Throwable $e) { $cmds = 0; }
    if (isset($_GET['import']) && $_GET['import'] === 'full') {
        try {
            $n = run_sql_file($pdo, __DIR__ . '/db/fulldb/devops_handbook.sql');
            step('ایمپورت دیتابیس کامل', true, $n . ' دستور اجرا شد');
            $cmds = (int)$pdo->query("SELECT COUNT(*) FROM commands")->fetchColumn();
        } catch (Throwable $e) {
            step('ایمپورت دیتابیس کامل', false, $e->getMessage());
        }
    }

    // 6) ساخت ادمین پیش‌فرض
    try {
        $admins = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();
        if ($admins === 0) {
            $pdo->prepare("INSERT INTO users (username, password_hash, role) VALUES (?,?,?)")
                ->execute(['admin', password_hash('Admin123!', PASSWORD_DEFAULT), 'admin']);
            step('ساخت کاربر ادمین', true, 'admin / Admin123!');
        } else {
            step('کاربر ادمین', true, 'از قبل موجود است (' . $admins . ' نفر)');
        }
    } catch (Throwable $e) {
        step('ساخت کاربر ادمین', false, $e->getMessage());
    }

    // آمار
    try {
        $stats = [
            'دستورات' => $pdo->query("SELECT COUNT(*) FROM commands")->fetchColumn(),
            'دسته‌بندی‌ها' => $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn(),
            'سؤال‌وجواب‌ها' => $pdo->query("SELECT COUNT(*) FROM chatbot_qa")->fetchColumn(),
            'کاربران' => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
        ];
    } catch (Throwable $e) { $stats = []; }
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>نصب DevOps Handbook v3</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:Tahoma,'Segoe UI',sans-serif;background:#0a1730;color:#eef1f7;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px}
.box{max-width:640px;width:100%;background:#10234a;border:1px solid rgba(212,175,55,.35);border-radius:20px;padding:36px;box-shadow:0 24px 70px rgba(0,0,0,.5)}
h1{color:#d4af37;text-align:center;margin-bottom:6px;font-size:1.5em}
.sub{text-align:center;color:#9aa6c0;margin-bottom:24px;font-size:.9em}
.step{display:flex;gap:10px;align-items:flex-start;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:12px 14px;margin-bottom:10px;font-size:.92em}
.step .ic{font-size:1.2em}
.step small{display:block;color:#9aa6c0;margin-top:4px;word-break:break-word}
.stats{display:flex;gap:10px;flex-wrap:wrap;margin:18px 0}
.stat{flex:1;min-width:120px;text-align:center;background:rgba(212,175,55,.1);border:1px solid rgba(212,175,55,.35);border-radius:12px;padding:12px}
.stat b{display:block;font-size:1.5em;color:#f1d87a}
.warn{background:rgba(220,53,69,.12);border:1px solid rgba(220,53,69,.5);border-radius:12px;padding:12px 14px;margin-top:16px;font-size:.88em}
.btns{display:flex;gap:10px;margin-top:18px;flex-wrap:wrap}
a.btn{flex:1;text-align:center;text-decoration:none;padding:12px;border-radius:12px;font-weight:bold}
.btn-gold{background:linear-gradient(135deg,#d4af37,#9a7b1e);color:#0a1730}
.btn-ghost{border:1px solid rgba(212,175,55,.5);color:#f1d87a}
</style>
</head>
<body>
<div class="box">
  <h1>⚙️ نصب DevOps Handbook v3</h1>
  <p class="sub">نصب و به‌روزرسانی خودکار دیتابیس</p>
  <?php foreach ($steps as $s): ?>
    <div class="step"><span class="ic"><?= $s['ok'] ? '✅' : '❌' ?></span>
      <div><?= esc($s['t']) ?><?php if ($s['d']): ?><small><?= esc($s['d']) ?></small><?php endif; ?></div>
    </div>
  <?php endforeach; ?>
  <?php if (!empty($stats)): ?>
    <div class="stats">
      <?php foreach ($stats as $k => $v): ?><div class="stat"><b><?= fa_digits($v) ?></b><span><?= esc($k) ?></span></div><?php endforeach; ?>
    </div>
  <?php endif; ?>
  <?php if ($pdo && ($stats['دستورات'] ?? 1) == 0): ?>
    <div class="btns"><a class="btn btn-ghost" href="?import=full">📥 ایمپورت دیتابیس آماده (۲۲۷ دستور)</a></div>
  <?php endif; ?>
  <div class="warn">⚠️ <b>مهم:</b> بعد از نصب موفق، فایل <code dir="ltr">install.php</code> را از روی هاست حذف کنید.</div>
  <div class="btns">
    <a class="btn btn-gold" href="index.php">🏠 ورود به سایت</a>
    <a class="btn btn-ghost" href="login.php">🔐 ورود ادمین</a>
  </div>
</div>
</body>
</html>
