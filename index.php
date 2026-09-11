<?php
require_once __DIR__ . '/config.php';
$me = current_user($pdo);

try {
    $stats = [
        'commands' => (int)$pdo->query("SELECT COUNT(*) FROM commands")->fetchColumn(),
        'categories' => (int)$pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn(),
        'qa' => (int)$pdo->query("SELECT COUNT(*) FROM chatbot_qa")->fetchColumn(),
        'searches' => (int)$pdo->query("SELECT COUNT(*) FROM search_logs")->fetchColumn(),
    ];
    $cats = $pdo->query(
        "SELECT c.category AS name, COUNT(cmd.id) AS cnt FROM categories c
         LEFT JOIN commands cmd ON cmd.category = c.category
         GROUP BY c.category ORDER BY cnt DESC")->fetchAll(PDO::FETCH_ASSOC);
    $recent = $pdo->query("SELECT id, command, description, category FROM commands ORDER BY id DESC LIMIT 6")->fetchAll(PDO::FETCH_ASSOC);
    $popular_q = $pdo->query("SELECT query, COUNT(*) c FROM search_logs GROUP BY query ORDER BY c DESC LIMIT 6")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $stats = ['commands' => 0, 'categories' => 0, 'qa' => 0, 'searches' => 0];
    $cats = []; $recent = []; $popular_q = [];
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>کتابخانه DevOps | مرجع دستورات لینوکس، داکر، کوبرنتیز و بیشتر</title>
<meta name="description" content="مرجع فارسی دستورات DevOps: لینوکس، داکر، کوبرنتیز، گیت، شبکه، دیتابیس — با جستجوی هوشمند و چت‌بات.">
<link rel="icon" href="assets/favicon.svg" type="image/svg+xml">
<link rel="stylesheet" href="assets/css/luxury.css?v=3.1">
</head>
<body>
<div class="topbar"><div class="topbar-inner">
  <a href="index.php" class="brand"><span class="brand-badge">⌘</span><span>کتابخانه <b>DevOps</b></span></a>
  <div class="topbar-links">
    <a href="index.php" class="on">🏠 خانه</a>
    <a href="user/index_readonly.php">📚 دستورات</a>
    <a href="user/chatbot.php">🤖 چت‌بات</a>
    <?php if ($me && $me['role'] === 'admin'): ?><a href="admin/index.php">🛠 پنل ادمین</a><?php endif; ?>
    <?php if ($me): ?>
      <span class="user-chip">👤 <?= esc($me['username']) ?></span>
      <a href="logout.php">🚪 خروج</a>
    <?php else: ?>
      <a href="login.php">🔐 ورود</a>
      <a href="register.php">✨ ثبت‌نام</a>
    <?php endif; ?>
  </div>
</div></div>

<div class="container">
  <div class="hero">
    <span class="hero-badge">✦ مرجع تخصصی متخصصان ✦</span>
    <h1>کتابخانه <span class="gold">DevOps</span></h1>
    <p>دستوری یادت رفته؟ در چند ثانیه پیداش کن — <?= fa_digits($stats['commands']) ?> دستور، جستجوی هوشمند و دستیار فارسی.</p>
    <div class="search-hero">
      <form action="user/search_readonly.php" method="GET">
        <input type="text" name="q" data-suggest="api/suggest.php" data-detail="user/command_detail_readonly.php"
               placeholder="بنویس: docker ps ، لاگ لینوکس ، بکاپ..." autocomplete="off">
        <button class="btn btn-gold" type="submit">🔍 جستجو</button>
      </form>
      <div class="suggest-drop"></div>
    </div>
    <div class="hero-feats">
      <span>⚡ جستجوی هوشمند فارسی</span>
      <span>🤖 چت‌بات پاسخ‌گو</span>
      <span>📚 <?= fa_digits($stats['commands']) ?> دستور کاربردی</span>
    </div>
  </div>

  <div class="stats-row">
    <div class="stat-card"><b><?= fa_digits($stats['commands']) ?></b><span>📚 دستور</span></div>
    <div class="stat-card"><b><?= fa_digits($stats['categories']) ?></b><span>🗂 دسته‌بندی</span></div>
    <div class="stat-card"><b><?= fa_digits($stats['qa']) ?></b><span>💬 سؤال‌وجواب</span></div>
    <div class="stat-card"><b><?= fa_digits($stats['searches']) ?></b><span>🔍 جستجو</span></div>
  </div>

  <h2 class="section-title">🗂 دسته‌بندی‌ها</h2>
  <div class="cat-grid">
    <?php foreach ($cats as $c): ?>
      <a class="cat-card" href="user/index_readonly.php?cat=<?= urlencode($c['name']) ?>">
        <span class="ic"><?= category_icon($c['name']) ?></span>
        <b><?= esc($c['name']) ?></b><span><?= fa_digits($c['cnt']) ?> دستور</span>
      </a>
    <?php endforeach; ?>
  </div>

  <h2 class="section-title">🆕 تازه‌ترین دستورات</h2>
  <div class="cmd-grid">
    <?php foreach ($recent as $r): ?>
      <a class="cmd-card" href="user/command_detail_readonly.php?id=<?= (int)$r['id'] ?>">
        <h3><?= esc($r['command']) ?></h3>
        <p><?= esc(mb_substr(strip_tags($r['description']), 0, 90)) ?>...</p>
        <div class="meta"><span class="pill"><?= category_icon($r['category']) ?> <?= esc($r['category']) ?></span><span>مشاهده ←</span></div>
      </a>
    <?php endforeach; ?>
  </div>

  <?php if ($popular_q): ?>
  <h2 class="section-title">🔥 پرجستجوترین‌ها</h2>
  <div class="categories">
    <?php foreach ($popular_q as $p): ?>
      <a class="cat-btn" href="user/search_readonly.php?q=<?= urlencode($p['query']) ?>"><?= esc($p['query']) ?></a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <h2 class="section-title">⚡ دسترسی سریع</h2>
  <div class="quick-links">
    <a class="quick-link" href="user/index_readonly.php"><span class="ic">📚</span><b>مرور دستورات</b><small>مشاهده و فیلتر همه دستورات</small></a>
    <a class="quick-link" href="user/chatbot.php"><span class="ic">🤖</span><b>چت‌بات هوشمند</b><small>بپرس، جواب بگیر</small></a>
    <a class="quick-link" href="user/search_readonly.php"><span class="ic">🔍</span><b>جستجوی پیشرفته</b><small>فیلتر دسته + مرتب‌سازی</small></a>
    <?php if ($me && $me['role'] === 'admin'): ?>
      <a class="quick-link" href="admin/index.php"><span class="ic">🛠</span><b>پنل مدیریت</b><small>مدیریت کامل سامانه</small></a>
    <?php else: ?>
      <a class="quick-link" href="login.php"><span class="ic">🔐</span><b>ورود مدیران</b><small>پنل مدیریت سامانه</small></a>
    <?php endif; ?>
  </div>
</div>

<div class="footer"><div class="footer-inner">
  <div><b>کتابخانه DevOps</b> — مرجع سریع دستورات متخصصان 🇮🇷</div>
  <div>نسخه <?= DH_VERSION ?> ✦ ساخته‌شده با ♥ برای جامعه DevOps ایران</div>
</div></div>

<script src="assets/js/app.js"></script>
</body>
</html>
