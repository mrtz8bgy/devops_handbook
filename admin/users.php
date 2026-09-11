<?php
require_once 'config.php';
require_once __DIR__ . '/../includes/layout.php';
require_admin($pdo);

$me = current_user($pdo);
$msg = ''; $err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $action = $_POST['action'] ?? '';
    $uid = (int)($_POST['id'] ?? 0);
    if ($uid === (int)$me['id']) {
        $err = 'نمی‌توانی خودت را تغییر دهی!';
    } elseif ($action === 'role' && in_array($_POST['role'] ?? '', ['admin', 'user'], true)) {
        $pdo->prepare("UPDATE users SET role = ? WHERE id = ?")->execute([$_POST['role'], $uid]);
        $msg = '✅ نقش کاربر به‌روز شد.';
    } elseif ($action === 'delete') {
        $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$uid]);
        $msg = '✅ کاربر حذف شد.';
    } elseif ($action === 'resetpw' && mb_strlen($_POST['newpass'] ?? '') >= 6) {
        $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?")
            ->execute([password_hash($_POST['newpass'], PASSWORD_DEFAULT), $uid]);
        $msg = '✅ رمز عبور ریست شد.';
    }
}

$users = $pdo->query("SELECT id, username, role, created_at FROM users ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

page_head('مدیریت کاربران');
topbar($pdo, admin_links('users'));
?>
<div class="container">
  <h2 class="section-title">👥 مدیریت کاربران (<?= fa_digits(count($users)) ?> نفر)</h2>
  <?php if ($msg): ?><div class="form-ok"><?= esc($msg) ?></div><?php endif; ?>
  <?php if ($err): ?><div class="form-err"><?= esc($err) ?></div><?php endif; ?>

  <div class="table-view">
    <table class="commands-table">
      <thead><tr><th>#</th><th>نام کاربری</th><th>نقش</th><th>عضویت</th><th>عملیات</th></tr></thead>
      <tbody>
      <?php foreach ($users as $i => $u): ?>
        <tr>
          <td><?= fa_digits($i + 1) ?></td>
          <td class="command-cell">👤 <?= esc($u['username']) ?><?= $u['id'] == $me['id'] ? ' <span class="pill">خودت</span>' : '' ?></td>
          <td><?= $u['role'] === 'admin' ? '<span class="category-badge-small">👑 ادمین</span>' : '<span class="pill">کاربر</span>' ?></td>
          <td style="font-size:.8em"><?= esc($u['created_at']) ?></td>
          <td>
            <?php if ($u['id'] != $me['id']): ?>
            <form method="POST" style="display:inline-flex;gap:6px;align-items:center;flex-wrap:wrap">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
              <select name="role" style="padding:6px 10px;border-radius:8px">
                <option value="user" <?= $u['role'] === 'user' ? 'selected' : '' ?>>کاربر</option>
                <option value="admin" <?= $u['role'] === 'admin' ? 'selected' : '' ?>>ادمین</option>
              </select>
              <button class="mini-btn mini-detail" name="action" value="role">💾 نقش</button>
              <input type="text" name="newpass" placeholder="رمز جدید" dir="ltr" style="width:110px;padding:6px 10px;border-radius:8px">
              <button class="mini-btn mini-detail" name="action" value="resetpw" data-confirm="رمز این کاربر ریست شود؟">🔑 ریست رمز</button>
              <button class="mini-btn" style="background:#e5485d;color:#fff" name="action" value="delete" data-confirm="کاربر «<?= esc($u['username']) ?>» حذف شود؟">🗑️</button>
            </form>
            <?php else: ?><span style="color:var(--muted);font-size:.8em">—</span><?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php page_footer(); ?>
