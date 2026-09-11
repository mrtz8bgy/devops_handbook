<?php
require_once 'config.php';
require_once __DIR__ . '/../includes/layout.php';

$q = trim($_GET['q'] ?? '');
$cat = trim($_GET['cat'] ?? '') ?: null;
$page = max(1, (int)($_GET['page'] ?? 1));
$me = current_user($pdo);

$cats = $pdo->query("SELECT category FROM categories ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
$res = ['results' => [], 'total' => 0, 'tokens' => [], 'pages' => 0, 'per' => 12];
if ($q !== '') {
    $res = pro_search($pdo, $q, ['category' => $cat, 'page' => $page, 'per' => 12,
        'log' => true, 'user_id' => $me['id'] ?? null]);
}

page_head($q !== '' ? 'جستجو: ' . $q : 'جستجوی پیشرفته');
topbar($pdo, user_links('search'));
?>
<div class="container">
  <div class="breadcrumb">🏠 <a href="../index.php">خانه</a> ← 🔍 جستجوی پیشرفته</div>

  <div class="search-hero" style="margin:10px auto 16px">
    <form action="search_readonly.php" method="GET">
      <input type="text" name="q" value="<?= esc($q) ?>" data-suggest="../api/suggest.php"
             data-detail="command_detail_readonly.php" placeholder="بنویس: docker ps ، لاگ ، بکاپ..." autocomplete="off">
      <button class="btn btn-gold" type="submit">🔍 جستجو</button>
    </form>
    <div class="suggest-drop"></div>
  </div>

  <?php if ($q !== ''): ?>
    <form method="GET" class="toolbar">
      <input type="hidden" name="q" value="<?= esc($q) ?>">
      <label style="font-size:.85em;color:var(--muted)">دسته:</label>
      <select name="cat" onchange="this.form.submit()">
        <option value="">همه دسته‌ها</option>
        <?php foreach ($cats as $c): ?>
          <option value="<?= esc($c) ?>" <?= $cat === $c ? 'selected' : '' ?>><?= esc($c) ?></option>
        <?php endforeach; ?>
      </select>
      <span class="pill">📊 <?= fa_digits($res['total']) ?> نتیجه</span>
    </form>

    <?php if ($res['results']): ?>
      <div class="cmd-grid">
        <?php foreach ($res['results'] as $r): ?>
          <a class="cmd-card" href="command_detail_readonly.php?id=<?= (int)$r['id'] ?>">
            <h3><?= highlight($r['command'], $res['tokens']) ?></h3>
            <p><?= highlight(mb_substr(strip_tags($r['description']), 0, 95) . '...', $res['tokens']) ?></p>
            <div class="meta">
              <span class="pill"><?= category_icon($r['category']) ?> <?= esc($r['category']) ?></span>
              <span>جزئیات ←</span>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
      <?php if ($res['pages'] > 1): ?>
        <div class="pager">
          <?php for ($i = 1; $i <= $res['pages']; $i++): ?>
            <?php $qs = http_build_query(['q' => $q, 'cat' => $cat ?? '', 'page' => $i]); ?>
            <?php if ($i === $page): ?><span class="on"><?= fa_digits($i) ?></span>
            <?php else: ?><a href="?<?= $qs ?>"><?= fa_digits($i) ?></a><?php endif; ?>
          <?php endfor; ?>
        </div>
      <?php endif; ?>
    <?php else: ?>
      <div class="empty-box">
        <h3>🤔 چیزی پیدا نشد!</h3>
        <p>کوتاه‌تر بنویس، انگلیسی امتحان کن، یا از <a href="chatbot.php">چت‌بات 🤖</a> بپرس.</p>
      </div>
    <?php endif; ?>
  <?php else: ?>
    <div class="empty-box">
      <h3>🔍 چیزی که دنبالشی رو تایپ کن...</h3>
      <p>مثال: <a href="?q=docker">docker</a> ، <a href="?q=لاگ">لاگ</a> ، <a href="?q=backup">backup</a> ، <a href="?q=nginx">nginx</a></p>
    </div>
  <?php endif; ?>
</div>
<?php page_footer(); ?>
