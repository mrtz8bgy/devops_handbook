<?php
require_once __DIR__ . '/config.php';

$me = current_user($pdo);
if ($me) safe_redirect($me['role'] === 'admin' ? 'admin/index.php' : 'index.php');

$error = '';
$next = $_GET['next'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $error = 'توکن امنیتی نامعتبر است. دوباره تلاش کنید.';
    } else {
        $u = attempt_login($pdo, $_POST['username'] ?? '', $_POST['password'] ?? '');
        if ($u) {
            $dest = $next !== '' ? $next : ($u['role'] === 'admin' ? 'admin/index.php' : 'index.php');
            safe_redirect($dest, 'index.php');
        }
        $error = 'نام کاربری یا رمز عبور اشتباه است.';
    }
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ورود | کتابخانه DevOps</title>
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
    <h1>🔐 ورود به حساب</h1>
    <p class="sub">به کتابخانه مرجع DevOps خوش برگشتی</p>
    <?php if ($error): ?><div class="form-err"><?= esc($error) ?></div><?php endif; ?>
    <form method="POST">
      <?= csrf_field() ?>
      <div class="field"><label>نام کاربری</label><input type="text" name="username" required autocomplete="username" dir="ltr" style="text-align:left"></div>
      <div class="field"><label>رمز عبور</label><input type="password" name="password" required autocomplete="current-password" dir="ltr" style="text-align:left"></div>
      <button class="btn btn-gold" style="width:100%" type="submit">ورود ✦</button>
    </form>
    <p class="auth-alt">حساب نداری؟ <a href="register.php">ثبت‌نام کن</a></p>
  </div>
</div>
</body>
</html>
