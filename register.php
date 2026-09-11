<?php
require_once __DIR__ . '/config.php';

$me = current_user($pdo);
if ($me) safe_redirect('index.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $error = 'توکن امنیتی نامعتبر است.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = (string)($_POST['password'] ?? '');
        if (mb_strlen($username) < 3 || !preg_match('/^[a-zA-Z0-9_.]+$/', $username)) {
            $error = 'نام کاربری حداقل ۳ کاراکتر و فقط حروف/عدد انگلیسی باشد.';
        } elseif (mb_strlen($password) < 6) {
            $error = 'رمز عبور حداقل ۶ کاراکتر باشد.';
        } else {
            try {
                $ex = $pdo->prepare("SELECT 1 FROM users WHERE username = ?");
                $ex->execute([$username]);
                if ($ex->fetch()) {
                    $error = 'این نام کاربری قبلاً ثبت شده است.';
                } else {
                    $pdo->prepare("INSERT INTO users (username, password_hash, role) VALUES (?,?,?)")
                        ->execute([$username, password_hash($password, PASSWORD_DEFAULT), 'user']);
                    attempt_login($pdo, $username, $password);
                    safe_redirect('index.php');
                }
            } catch (Exception $e) {
                $error = 'خطا در ثبت‌نام. اول صفحه نصب (install.php) را اجرا کنید.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ثبت‌نام | کتابخانه DevOps</title>
<link rel="icon" href="assets/favicon.svg" type="image/svg+xml">
<link rel="stylesheet" href="assets/css/luxury.css?v=3.1">
</head>
<body>
<div class="topbar"><div class="topbar-inner">
  <a href="index.php" class="brand"><span class="brand-badge">⌘</span><span>کتابخانه <b>DevOps</b></span></a>
  <div class="topbar-links"><a href="index.php">🏠 خانه</a></div>
</div></div>

<div class="auth-wrap">
  <div class="auth-card">
    <h1>✨ ساخت حساب کاربری</h1>
    <p class="sub">عضو کتابخانه مرجع DevOps شو</p>
    <?php if ($error): ?><div class="form-err"><?= esc($error) ?></div><?php endif; ?>
    <form method="POST">
      <?= csrf_field() ?>
      <div class="field"><label>نام کاربری (انگلیسی)</label><input type="text" name="username" required dir="ltr" style="text-align:left" placeholder="e.g. arash_dev"></div>
      <div class="field"><label>رمز عبور (حداقل ۶ کاراکتر)</label><input type="password" name="password" required dir="ltr" style="text-align:left"></div>
      <button class="btn btn-gold" style="width:100%" type="submit">ثبت‌نام ✦</button>
    </form>
    <p class="auth-alt">قبلاً ثبت‌نام کردی؟ <a href="login.php">وارد شو</a></p>
  </div>
</div>
</body>
</html>
