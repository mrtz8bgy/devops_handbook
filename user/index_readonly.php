<?php
require_once 'config.php';
require_once __DIR__ . '/../includes/layout.php';

$cat = trim($_GET['cat'] ?? 'all');
$page = max(1, (int)($_GET['page'] ?? 1));
$per = 12;

$cats = $pdo->query(
    "SELECT c.category AS name, COUNT(cmd.id) AS cnt FROM categories c
     LEFT JOIN commands cmd ON cmd.category = c.category
     GROUP BY c.category ORDER BY c.category")->fetchAll(PDO::FETCH_ASSOC);

if ($cat !== 'all') {
    $st = $pdo->prepare("SELECT COUNT(*) FROM commands WHERE category = ?");
    $st->execute([$cat]);
    $total = (int)$st->fetchColumn();
    $st = $pdo->prepare("SELECT * FROM commands WHERE category = ? ORDER BY command ASC LIMIT $per OFFSET " . (($page - 1) * $per));
    $st->execute([$cat]);
} else {
    $total = (int)$pdo->query("SELECT COUNT(*) FROM commands")->fetchColumn();
    $st = $pdo->query("SELECT * FROM commands ORDER BY id DESC LIMIT $per OFFSET " . (($page - 1) * $per));
}
$rows = $st->fetchAll(PDO::FETCH_ASSOC);
$pages = (int)ceil($total / $per);
$total_all = (int)$pdo->query("SELECT COUNT(*) FROM commands")->fetchColumn();

page_head('مرور دستورات', 'مرور و فیلتر همه دستورات DevOps بر اساس دسته‌بندی');
topbar($pdo, user_links('browse'));
?>
<div class="container">
  <div class="breadcrumb">🏠 <a href="../index.php">خانه</a> ← 📚 مرور دستورات <?= $cat !== 'all' ? '← ' . esc($cat) : '' ?></div>

  <div class="search-hero" style="margin:10px auto 20px">
    <form action="search_readonly.php" method="GET">
      <input type="text" name="q" data-suggest="../api/suggest.php" data-detail="command_detail_readonly.php"
             placeholder="جستجوی سریع دستور..." autocomplete="off">
      <button class="btn btn-gold" type="submit">🔍</button>
    </form>
    <div class="suggest-drop"></div>
  </div>

  <div class="categories">
    <a class="cat-btn <?= $cat === 'all' ? 'active' : '' ?>" href="?cat=all">همه (<?= fa_digits($total_all ?? $pdo->query("SELECT COUNT(*) FROM commands")->fetchColumn()) ?>)</a>
    <?php foreach ($cats as $c): ?>
      <a class="cat-btn <?= $cat === $c['name'] ? 'active' : '' ?>" href="?cat=<?= urlencode($c['name']) ?>">
        <?= category_icon($c['name']) ?> <?= esc($c['name']) ?> (<?= fa_digits($c['cnt']) ?>)
      </a>
    <?php endforeach; ?>
  </div>

  <?php if ($rows): ?>
    <div class="cmd-grid">
      <?php foreach ($rows as $r): ?>
        <a class="cmd-card" href="command_detail_readonly.php?id=<?= (int)$r['id'] ?>">
          <h3><?= esc($r['command']) ?></h3>
          <p><?= esc(mb_substr(strip_tags($r['description']), 0, 95)) ?>...</p>
          <div class="meta">
            <span class="pill"><?= category_icon($r['category']) ?> <?= esc($r['category']) ?></span>
            <span>جزئیات ←</span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
    <?php if ($pages > 1): ?>
      <div class="pager">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
          <?php if ($i === $page): ?><span class="on"><?= fa_digits($i) ?></span>
          <?php else: ?><a href="?cat=<?= urlencode($cat) ?>&page=<?= $i ?>"><?= fa_digits($i) ?></a><?php endif; ?>
        <?php endfor; ?>
      </div>
    <?php endif; ?>
  <?php else: ?>
    <div class="empty-box"><h3>📭 دستوری در این دسته نیست</h3><p>دسته دیگری را امتحان کن.</p></div>
  <?php endif; ?>
</div>
<?php page_footer(); ?>
