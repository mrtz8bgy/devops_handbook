<?php
require_once 'config.php';
require_once __DIR__ . '/../includes/layout.php';
require_admin($pdo);

$me = current_user($pdo);
$limit = 15;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;
$selected_category = $_GET['cat'] ?? 'all';

if ($selected_category !== 'all') {
    $st = $pdo->prepare("SELECT COUNT(*) FROM commands WHERE category = ?");
    $st->execute([$selected_category]);
    $total_records = (int)$st->fetchColumn();
    $st = $pdo->prepare("SELECT * FROM commands WHERE category = ? ORDER BY command ASC LIMIT $limit OFFSET $offset");
    $st->execute([$selected_category]);
} else {
    $total_records = (int)$pdo->query("SELECT COUNT(*) FROM commands")->fetchColumn();
    $st = $pdo->query("SELECT * FROM commands ORDER BY id DESC LIMIT $limit OFFSET $offset");
}
$commands = $st->fetchAll(PDO::FETCH_ASSOC);
$total_pages = (int)ceil($total_records / $limit);

$stats = [
    'commands' => $total_records,
    'categories' => (int)$pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn(),
    'qa' => (int)$pdo->query("SELECT COUNT(*) FROM chatbot_qa")->fetchColumn(),
    'users' => (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
];
try { $stats['searches'] = (int)$pdo->query("SELECT COUNT(*) FROM search_logs")->fetchColumn(); }
catch (Exception $e) { $stats['searches'] = 0; }
try { $stats['unknown'] = (int)$pdo->query("SELECT COUNT(*) FROM chatbot_unknown_questions WHERE status = 'pending'")->fetchColumn(); }
catch (Exception $e) { $stats['unknown'] = 0; }

$cats = $pdo->query("SELECT category FROM categories ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
try {
    $top_q = $pdo->query("SELECT query, COUNT(*) c FROM search_logs GROUP BY query ORDER BY c DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) { $top_q = []; }

page_head('داشبورد مدیریت');
topbar($pdo, admin_links('dash'));
?>
<div class="container">
  <h2 class="section-title">👑 داشبورد — خوش اومدی <?= esc($me['username']) ?></h2>

  <div class="dash-grid">
    <div class="dash-card"><div class="n"><?= fa_digits($stats['commands']) ?></div><div class="l">📚 دستورات</div></div>
    <div class="dash-card"><div class="n"><?= fa_digits($stats['categories']) ?></div><div class="l">🗂 دسته‌بندی‌ها</div></div>
    <div class="dash-card"><div class="n"><?= fa_digits($stats['qa']) ?></div><div class="l">💬 سؤال‌وجواب‌ها</div></div>
    <div class="dash-card"><div class="n"><?= fa_digits($stats['users']) ?></div><div class="l">👥 کاربران</div></div>
    <div class="dash-card"><div class="n"><?= fa_digits($stats['searches']) ?></div><div class="l">🔍 جستجوها</div></div>
    <div class="dash-card"><div class="n"><?= fa_digits($stats['unknown']) ?></div><div class="l">❓ بی‌جواب‌ها</div></div>
  </div>

  <div class="quick-links">
    <a class="quick-link" href="add.php"><span class="ic">➕</span><b>افزودن دستور</b><small>دستور جدید + فایل</small></a>
    <a class="quick-link" href="search.php"><span class="ic">🔍</span><b>جستجو</b><small>یافتن سریع دستور</small></a>
    <a class="quick-link" href="manage_categories.php"><span class="ic">🏷️</span><b>دسته‌بندی‌ها</b><small>افزودن / حذف</small></a>
    <a class="quick-link" href="chatbot.php"><span class="ic">🤖</span><b>تست چت‌بات</b><small>گفتگو با دستیار</small></a>
    <a class="quick-link" href="manage_qa.php"><span class="ic">📋</span><b>مدیریت QA</b><small>سؤال‌وجواب‌های ربات</small></a>
    <a class="quick-link" href="manage_unknown.php"><span class="ic">❓</span><b>بی‌جواب‌ها (<?= fa_digits($stats['unknown']) ?>)</b><small>یادگیری از کاربران</small></a>
    <a class="quick-link" href="users.php"><span class="ic">👥</span><b>کاربران</b><small>نقش‌ها و دسترسی</small></a>
    <a class="quick-link" href="change_password.php"><span class="ic">🔑</span><b>تغییر رمز</b><small>رمز عبور خودت</small></a>
    <a class="quick-link" href="ai_drafts.php"><span class="ic">🤖</span><b>هوش مصنوعی</b><small>صف تأیید و تنظیمات</small></a>
    <a class="quick-link" href="../index.php" target="_blank"><span class="ic">🌐</span><b>مشاهده سایت</b><small>نسخه عمومی</small></a>
  </div>

  <h2 class="section-title">📚 مرور دستورات</h2>
  <div class="search-hero" style="margin:0 0 14px">
    <form action="search.php" method="GET">
      <input type="text" name="q" placeholder="جستجو در دستورات...">
      <button class="btn btn-gold" type="submit">🔍</button>
    </form>
  </div>
  <div class="categories">
    <a class="cat-btn <?= $selected_category === 'all' ? 'active' : '' ?>" href="?cat=all">همه</a>
    <?php foreach ($cats as $c): ?>
      <a class="cat-btn <?= $selected_category === $c ? 'active' : '' ?>" href="?cat=<?= urlencode($c) ?>&page=1"><?= esc($c) ?></a>
    <?php endforeach; ?>
  </div>

  <div class="table-view">
    <table class="commands-table">
      <thead><tr><th>#</th><th>دستور</th><th>توضیحات</th><th>دسته</th><th>عملیات</th></tr></thead>
      <tbody>
      <?php if ($commands): foreach ($commands as $i => $row): ?>
        <tr>
          <td><?= fa_digits($offset + $i + 1) ?></td>
          <td class="command-cell"><?= esc($row['command']) ?></td>
          <td><?= esc(mb_substr($row['description'], 0, 60)) ?>...</td>
          <td><span class="category-badge-small"><?= esc($row['category']) ?></span></td>
          <td class="action-buttons-cell">
            <button class="action-icon copy-icon" data-copy="<?= esc($row['command']) ?>" title="کپی">📋</button>
            <a class="action-icon detail-icon" style="display:inline-block;text-decoration:none" href="command_detail.php?id=<?= (int)$row['id'] ?>" title="جزئیات">🔍</a>
            <a class="action-icon edit-icon" style="display:inline-block;text-decoration:none" href="edit_command.php?id=<?= (int)$row['id'] ?>" title="ویرایش">✏️</a>
            <a class="action-icon delete-icon" style="display:inline-block;text-decoration:none" href="delete_command.php?id=<?= (int)$row['id'] ?>" title="حذف">🗑️</a>
          </td>
        </tr>
      <?php endforeach; else: ?>
        <tr><td colspan="5" style="text-align:center;padding:36px">📭 دستوری یافت نشد! <a href="add.php">➕ افزودن دستور</a></td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>

  <?php if ($total_pages > 1): ?>
    <div class="pager">
      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <?php if ($i === $page): ?><span class="on"><?= fa_digits($i) ?></span>
        <?php else: ?><a href="?cat=<?= urlencode($selected_category) ?>&page=<?= $i ?>"><?= fa_digits($i) ?></a><?php endif; ?>
      <?php endfor; ?>
    </div>
  <?php endif; ?>

  <?php if ($top_q): ?>
    <h2 class="section-title">🔥 پرجستجوترین کاربران</h2>
    <div class="categories">
      <?php foreach ($top_q as $t): ?>
        <a class="cat-btn" href="search.php?q=<?= urlencode($t['query']) ?>"><?= esc($t['query']) ?> (<?= fa_digits($t['c']) ?>)</a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
<script>
document.querySelectorAll('[data-copy]').forEach(b => b.addEventListener('click', () => copyText(b.dataset.copy, '✅ کپی شد!')));
</script>
<?php page_footer(); ?>
