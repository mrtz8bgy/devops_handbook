<?php
require_once 'config.php';
require_once __DIR__ . '/../includes/layout.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM commands WHERE id = ?");
$stmt->execute([$id]);
$command = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$command) safe_redirect('index_readonly.php');

$sf = $pdo->prepare("SELECT * FROM command_files WHERE command_id = ? ORDER BY uploaded_at DESC");
$sf->execute([$id]);
$attached_files = $sf->fetchAll(PDO::FETCH_ASSOC);

$rel = $pdo->prepare("SELECT id, command, description FROM commands WHERE category = ? AND id != ? ORDER BY id DESC LIMIT 4");
$rel->execute([$command['category'], $id]);
$related = $rel->fetchAll(PDO::FETCH_ASSOC);

page_head($command['command'], mb_substr(strip_tags($command['description']), 0, 150));
topbar($pdo, user_links('browse'));
?>
<div class="container">
  <div class="breadcrumb">🏠 <a href="../index.php">خانه</a> ← 📚 <a href="index_readonly.php?cat=<?= urlencode($command['category']) ?>"><?= esc($command['category']) ?></a> ← <code dir="ltr"><?= esc($command['command']) ?></code></div>

  <div class="detail-container" style="margin-top:14px">
    <div class="detail-header">
      <div class="readonly-badge-header">🔍 نسخه عمومی</div>
      <h1 dir="ltr">$ <?= esc($command['command']) ?></h1>
      <span class="detail-category"><?= category_icon($command['category']) ?> <?= esc($command['category']) ?></span>
      <div>
        <button class="detail-copy-btn" data-copy="<?= esc($command['command']) ?>">📋 کپی دستور</button>
      </div>
    </div>
    <div class="detail-body">
      <div class="info-section">
        <h3>📖 توضیحات</h3>
        <p><?= nl2br(esc($command['description'])) ?></p>
      </div>

      <?php if (!empty($command['example'])): ?>
      <div class="info-section">
        <h3>💡 مثال کاربردی</h3>
        <pre><?= esc($command['example']) ?></pre>
        <button class="btn btn-ghost btn-sm" data-copy="<?= esc($command['example']) ?>" style="margin-top:8px">📋 کپی مثال</button>
      </div>
      <?php endif; ?>

      <?php if (!empty($command['keywords'])): ?>
      <div class="info-section">
        <h3>🔑 کلمات کلیدی</h3>
        <div class="keyword-list">
          <?php foreach (explode(',', $command['keywords']) as $kw): $kw = trim($kw); if ($kw === '') continue; ?>
            <a class="keyword-badge" style="text-decoration:none" href="search_readonly.php?q=<?= urlencode($kw) ?>">#<?= esc($kw) ?></a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <?php if (!empty($command['faq'])): ?>
      <div class="info-section">
        <h3>❓ سؤالات متداول</h3>
        <p><?= nl2br(esc($command['faq'])) ?></p>
      </div>
      <?php endif; ?>

      <?php if (!empty($command['troubleshooting'])): ?>
      <div class="info-section">
        <h3>🛠 عیب‌یابی</h3>
        <p><?= nl2br(esc($command['troubleshooting'])) ?></p>
      </div>
      <?php endif; ?>

      <?php if (!empty($command['similar_commands'])): ?>
      <div class="info-section">
        <h3>🔗 دستورات مشابه</h3>
        <div class="similar-commands">
          <?php foreach (explode(',', $command['similar_commands']) as $sm): $sm = trim($sm); if ($sm === '') continue; ?>
            <a class="similar-command" href="search_readonly.php?q=<?= urlencode($sm) ?>"><code dir="ltr"><?= esc($sm) ?></code></a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <?php if ($attached_files): ?>
      <div class="info-section">
        <h3>📎 فایل‌های پیوست</h3>
        <div class="similar-commands">
          <?php foreach ($attached_files as $f): ?>
            <a class="file-item" target="_blank" href="<?= esc($f['file_path']) ?>">📄 <?= esc($f['file_name']) ?></a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <?php if ($related): ?>
      <div class="info-section">
        <h3>📚 مرتبط در همین دسته</h3>
        <div class="similar-commands">
          <?php foreach ($related as $r): ?>
            <a class="similar-command" href="command_detail_readonly.php?id=<?= (int)$r['id'] ?>"><code dir="ltr"><?= esc($r['command']) ?></code></a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <div style="text-align:center;margin-top:10px">
        <a href="index_readonly.php" class="back-btn">🔙 بازگشت به دستورات</a>
      </div>
    </div>
  </div>
</div>
<script>
document.querySelectorAll('[data-copy]').forEach(b => b.addEventListener('click', () => copyText(b.dataset.copy, '✅ کپی شد!')));
</script>
<?php page_footer(); ?>
