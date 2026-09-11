<?php
// ============================================================
// DevOps Handbook v3 — تغییر رمز عبور ادمین واردشده
// ============================================================
require_once 'config.php';
require_once __DIR__ . '/../includes/layout.php';
require_admin($pdo);

$me = current_user($pdo);
$msg = ''; $err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $cur = (string)($_POST['current'] ?? '');
    $new = (string)($_POST['new'] ?? '');
    $cfm = (string)($_POST['confirm'] ?? '');

    $st = $pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
    $st->execute([(int)$me['id']]);
    $hash = (string)$st->fetchColumn();

    if (!password_verify($cur, $hash)) {
        $err = 'رمز فعلی اشتباه است.';
    } elseif (mb_strlen($new) < 6) {
        $err = 'رمز جدید باید حداقل ۶ کاراکتر باشد.';
    } elseif ($new !== $cfm) {
        $err = 'تکرار رمز جدید مطابقت ندارد.';
    } elseif (password_verify($new, $hash)) {
        $err = 'رمز جدید نباید با رمز فعلی یکی باشد.';
    } else {
        $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?")
            ->execute([password_hash($new, PASSWORD_DEFAULT), (int)$me['id']]);
        $msg = '✅ رمز عبور با موفقیت تغییر کرد.';
    }
}

page_head('تغییر رمز عبور');
topbar($pdo, admin_links('password'));
?>
<div class="container" style="max-width:540px">
  <h2 class="section-title">🔑 تغییر رمز عبور</h2>
  <p style="color:var(--muted)">👤 <?= esc($me['username']) ?> — این رمز برای ورودهای بعدی استفاده می‌شود.</p>
  <?php if ($msg): ?><div class="form-ok"><?= esc($msg) ?></div><?php endif; ?>
  <?php if ($err): ?><div class="form-err"><?= esc($err) ?></div><?php endif; ?>

  <form method="POST" class="auth-card" style="margin-top:14px">
    <?= csrf_field() ?>
    <div class="field"><label>رمز فعلی</label><input type="password" name="current" required dir="ltr" style="text-align:left" autocomplete="current-password"></div>
    <div class="field"><label>رمز جدید (حداقل ۶ کاراکتر)</label><input type="password" name="new" required dir="ltr" style="text-align:left" autocomplete="new-password"></div>
    <div class="field"><label>تکرار رمز جدید</label><input type="password" name="confirm" required dir="ltr" style="text-align:left" autocomplete="new-password"></div>
    <button class="btn btn-gold" style="width:100%" type="submit">💾 ذخیره رمز جدید</button>
  </form>
  <p style="margin-top:12px"><a href="index.php" style="color:var(--gold)">→ بازگشت به داشبورد</a></p>
</div>
<?php page_footer(); ?>
