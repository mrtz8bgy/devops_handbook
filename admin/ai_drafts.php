<?php
// ============================================================
// DevOps Handbook v4 — کنسول هوش مصنوعی: تنظیمات + صف تأیید
// ============================================================
require_once 'config.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/ai_provider.php';
require_admin($pdo);

$msg = ''; $err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $action = $_POST['action'] ?? '';
    if ($action === 'settings') {
        ai_save_settings($pdo, $_POST);
        $msg = '✅ تنظیمات هوش مصنوعی ذخیره شد.';
    } elseif ($action === 'approve' || $action === 'approve_edit') {
        $id = (int)($_POST['id'] ?? 0);
        $st = $pdo->prepare("SELECT * FROM ai_drafts WHERE id = ? AND status = 'ready'");
        $st->execute([$id]);
        $d = $st->fetch(PDO::FETCH_ASSOC);
        $cats = ['general', 'devops', 'guide', 'education', 'coding', 'info'];
        $cat = in_array($_POST['category'] ?? '', $cats, true) ? $_POST['category'] : 'general';
        $answer = $action === 'approve_edit' ? trim((string)($_POST['answer'] ?? '')) : (string)($d['answer'] ?? '');
        if (!$d) $err = 'پیش‌نویس آماده پیدا نشد.';
        elseif ($answer === '') $err = 'متن پاسخ خالی است.';
        else {
            $pdo->prepare("INSERT INTO chatbot_qa (question, answer, keywords, category, usage_count, source, ai_model) VALUES (?,?,?,?,0,?,?)")
                ->execute([$d['question'], $answer, '', $cat, 'ai', $d['model']]);
            $pdo->prepare("UPDATE ai_drafts SET status = 'approved' WHERE id = ?")->execute([$id]);
            $msg = '✅ تأیید شد و به دانش ربات اضافه شد — از این به بعد خودکار جواب داده می‌شود.';
        }
    } elseif ($action === 'reject') {
        $pdo->prepare("UPDATE ai_drafts SET status = 'rejected' WHERE id = ?")->execute([(int)($_POST['id'] ?? 0)]);
        $msg = '🗑️ پیش‌نویس رد شد.';
    } elseif ($action === 'delete') {
        $pdo->prepare("DELETE FROM ai_drafts WHERE id = ?")->execute([(int)($_POST['id'] ?? 0)]);
        $msg = '🗑️ حذف شد.';
    } elseif ($action === 'retry') {
        $pdo->prepare("UPDATE ai_drafts SET status = 'queued', tries = 0, error = NULL WHERE id = ?")->execute([(int)($_POST['id'] ?? 0)]);
        $msg = '🔄 به صف تولید برگشت.';
    }
}

$s = ai_settings($pdo);
$today = ai_today_count($pdo);
$limit = max(1, (int)$s['ai_daily_limit']);
$counts = ['queued' => 0, 'ready' => 0, 'approved' => 0, 'rejected' => 0, 'failed' => 0];
try {
    foreach ($pdo->query("SELECT status, COUNT(*) c FROM ai_drafts GROUP BY status")->fetchAll(PDO::FETCH_ASSOC) as $r) {
        $counts[$r['status']] = (int)$r['c'];
    }
} catch (Exception $e) {}
$drafts = [];
try {
    $drafts = $pdo->query("SELECT * FROM ai_drafts ORDER BY FIELD(status,'queued','failed','ready','approved','rejected'), id DESC LIMIT 100")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) { $err = 'جدول ai_drafts نیست — install.php را اجرا کن.'; }

$st_pill = function ($st) {
    switch ($st) {
        case 'queued':
            return '<span class="pill">⏳ در صف</span>';
        case 'ready':
            return '<span class="pill" style="background:rgba(47,191,113,.15);border-color:rgba(47,191,113,.5);color:#9ff0c6">✨ آماده تأیید</span>';
        case 'approved':
            return '<span class="pill">✅ تأییدشده</span>';
        case 'rejected':
            return '<span class="pill" style="background:rgba(229,72,93,.12);border-color:rgba(229,72,93,.5);color:#ffb3bd">✖ ردشده</span>';
        case 'failed':
            return '<span class="pill" style="background:rgba(229,72,93,.12);border-color:rgba(229,72,93,.5);color:#ffb3bd">⚠ ناموفق</span>';
        default:
            return '<span class="pill">' . esc($st) . '</span>';
    }
};

page_head('هوش مصنوعی و یادگیری');
topbar($pdo, admin_links('ai'));
?>
<div class="container">
  <h2 class="section-title">🤖 هوش مصنوعی و یادگیری خودکار</h2>
  <?php if ($msg): ?><div class="form-ok"><?= esc($msg) ?></div><?php endif; ?>
  <?php if ($err): ?><div class="form-err"><?= esc($err) ?></div><?php endif; ?>

  <div class="dash-grid">
    <div class="dash-card"><div class="n"><?= fa_digits($counts['queued'] + $counts['failed']) ?></div><div class="l">⏳ در صف تولید</div></div>
    <div class="dash-card"><div class="n"><?= fa_digits($counts['ready']) ?></div><div class="l">✨ آماده تأیید</div></div>
    <div class="dash-card"><div class="n"><?= fa_digits($counts['approved']) ?></div><div class="l">✅ تأییدشده (دانش ربات)</div></div>
    <div class="dash-card"><div class="n"><?= fa_digits($today) . '/' . fa_digits($limit) ?></div><div class="l">📊 مصرف امروز / سقف</div></div>
  </div>

  <h3 class="section-title" style="font-size:1.05em">⚙️ تنظیمات</h3>
  <form method="POST" class="auth-card" style="max-width:100%;margin-bottom:10px">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="settings">
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px">
      <div class="field"><label>وضعیت</label>
        <select name="ai_enabled" style="width:100%;padding:11px;border-radius:12px;background:rgba(6,13,26,.7);border:1px solid rgba(255,255,255,.14);color:#fff;font-family:inherit">
          <option value="1" <?= $s['ai_enabled'] === '1' ? 'selected' : '' ?>>✅ روشن</option>
          <option value="0" <?= $s['ai_enabled'] !== '1' ? 'selected' : '' ?>>⛔ خاموش</option>
        </select>
      </div>
      <div class="field"><label>ارائه‌دهنده</label>
        <select name="ai_provider" style="width:100%;padding:11px;border-radius:12px;background:rgba(6,13,26,.7);border:1px solid rgba(255,255,255,.14);color:#fff;font-family:inherit">
          <option value="ollama" <?= $s['ai_provider'] === 'ollama' ? 'selected' : '' ?>>🦙 اولاما (لوکال، رایگان)</option>
          <option value="openai" <?= $s['ai_provider'] === 'openai' ? 'selected' : '' ?>>🔌 API سازگار با OpenAI</option>
          <option value="mock" <?= $s['ai_provider'] === 'mock' ? 'selected' : '' ?>>🧪 آزمایشی (mock)</option>
        </select>
      </div>
      <div class="field"><label>آدرس اولاما</label><input type="text" name="ai_ollama_url" value="<?= esc($s['ai_ollama_url']) ?>" dir="ltr" style="text-align:left"></div>
      <div class="field"><label>مدل اولاما</label><input type="text" name="ai_ollama_model" value="<?= esc($s['ai_ollama_model']) ?>" dir="ltr" style="text-align:left" placeholder="qwen2.5:3b"></div>
      <div class="field"><label>آدرس API</label><input type="text" name="ai_api_url" value="<?= esc($s['ai_api_url']) ?>" dir="ltr" style="text-align:left"></div>
      <div class="field"><label>مدل API</label><input type="text" name="ai_api_model" value="<?= esc($s['ai_api_model']) ?>" dir="ltr" style="text-align:left"></div>
      <div class="field"><label>سقف روزانه</label><input type="text" name="ai_daily_limit" value="<?= esc($s['ai_daily_limit']) ?>" dir="ltr" style="text-align:left"></div>
      <div class="field"><label>تایم‌اوت (ثانیه)</label><input type="text" name="ai_timeout" value="<?= esc($s['ai_timeout']) ?>" dir="ltr" style="text-align:left"></div>
    </div>
    <p style="font-size:.8em;color:var(--muted)">🔑 کلید API فقط از متغیر محیطی <code dir="ltr">AI_API_KEY</code> خوانده می‌شود و در دیتابیس ذخیره نمی‌شود. <?= $s['ai_api_key'] !== '' ? '✅ (کلید شناسایی شد)' : '⚠ (کلیدی ست نشده)' ?></p>
    <button class="btn btn-gold" type="submit">💾 ذخیره تنظیمات</button>
  </form>

  <h3 class="section-title" style="font-size:1.05em">📝 صف پیش‌نویس‌ها (<?= fa_digits(count($drafts)) ?>)</h3>
  <div id="ai-csrf" style="display:none"><?= csrf_field() ?></div>
  <div class="table-view">
    <table class="commands-table">
      <thead><tr><th>#</th><th>سؤال</th><th>وضعیت</th><th>پاسخ / خطا</th><th>مدل</th><th>عملیات</th></tr></thead>
      <tbody>
      <?php foreach ($drafts as $i => $d): ?>
        <tr>
          <td><?= fa_digits($i + 1) ?></td>
          <td class="command-cell" style="max-width:260px"><?= esc($d['question']) ?></td>
          <td><?= $st_pill($d['status']) ?><?= $d['tries'] > 0 ? ' <small style="color:var(--muted)">(' . fa_digits($d['tries']) . ' تلاش)</small>' : '' ?></td>
          <td style="max-width:320px;font-size:.82em">
            <?php if ($d['answer']): ?>
              <details><summary style="cursor:pointer;color:var(--gold-light)"><?= esc(mb_substr($d['answer'], 0, 80)) ?>…</summary>
                <div style="white-space:pre-wrap;margin-top:6px"><?= esc($d['answer']) ?></div>
              </details>
            <?php elseif ($d['error']): ?><span style="color:#ffb3bd"><?= esc($d['error']) ?></span>
            <?php else: ?><span style="color:var(--muted)">—</span><?php endif; ?>
          </td>
          <td style="font-size:.78em" dir="ltr"><?= esc($d['model'] ?: ($d['provider'] ?: '—')) ?></td>
          <td>
            <div style="display:flex;gap:6px;flex-wrap:wrap;align-items:center">
              <?php if (in_array($d['status'], ['queued', 'failed'], true)): ?>
                <button class="mini-btn mini-detail ai-gen" data-id="<?= (int)$d['id'] ?>">⚡ تولید</button>
              <?php endif; ?>
              <?php if ($d['status'] === 'ready'): ?>
                <form method="POST" style="display:inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="action" value="approve"><input type="hidden" name="id" value="<?= (int)$d['id'] ?>">
                  <select name="category" style="padding:5px 8px;border-radius:8px;font-size:.78em">
                    <option value="general">general</option><option value="devops">devops</option>
                    <option value="guide">guide</option><option value="education">education</option>
                    <option value="coding">coding</option><option value="info">info</option>
                  </select>
                  <button class="mini-btn mini-copy">✅ تأیید</button>
                </form>
                <details style="display:inline"><summary class="mini-btn mini-detail" style="cursor:pointer;list-style:none">✏️ ویرایش+تأیید</summary>
                  <form method="POST" style="margin-top:6px;min-width:260px">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="approve_edit"><input type="hidden" name="id" value="<?= (int)$d['id'] ?>">
                    <textarea name="answer" rows="4" style="width:100%;border-radius:10px;padding:8px;background:rgba(6,13,26,.7);border:1px solid rgba(255,255,255,.14);color:#fff;font-family:inherit"><?= esc($d['answer']) ?></textarea>
                    <div style="margin-top:6px;display:flex;gap:6px">
                      <select name="category" style="padding:5px 8px;border-radius:8px;font-size:.78em">
                        <option value="general">general</option><option value="devops">devops</option>
                        <option value="guide">guide</option><option value="education">education</option>
                        <option value="coding">coding</option><option value="info">info</option>
                      </select>
                      <button class="mini-btn mini-copy">✅ تأیید ویرایش‌شده</button>
                    </div>
                  </form>
                </details>
              <?php endif; ?>
              <?php if ($d['status'] === 'failed'): ?>
                <form method="POST" style="display:inline"><?= csrf_field() ?>
                  <input type="hidden" name="action" value="retry"><input type="hidden" name="id" value="<?= (int)$d['id'] ?>">
                  <button class="mini-btn mini-detail">🔄 تلاش مجدد</button>
                </form>
              <?php endif; ?>
              <?php if (in_array($d['status'], ['ready', 'queued'], true)): ?>
                <form method="POST" style="display:inline"><?= csrf_field() ?>
                  <input type="hidden" name="action" value="reject"><input type="hidden" name="id" value="<?= (int)$d['id'] ?>">
                  <button class="mini-btn" style="background:#e5485d;color:#fff">✖ رد</button>
                </form>
              <?php endif; ?>
              <form method="POST" style="display:inline" data-confirm="حذف شود؟"><?= csrf_field() ?>
                <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$d['id'] ?>">
                <button class="mini-btn" style="background:rgba(255,255,255,.1);color:#fff">🗑️</button>
              </form>
            </div>
            <span class="ai-msg" style="font-size:.78em"></span>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$drafts): ?><tr><td colspan="6" style="text-align:center;color:var(--muted)">هنوز پیش‌نویسی نیست — سؤال‌های بی‌جواب کاربران خودکار اینجا می‌آیند 🤖</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<script>
document.querySelectorAll('.ai-gen').forEach(btn => {
  btn.addEventListener('click', async () => {
    const csrf = document.querySelector('#ai-csrf input').value;
    const msg = btn.closest('td').querySelector('.ai-msg');
    btn.disabled = true; btn.textContent = '⏳ در حال تولید…';
    msg.textContent = '';
    try {
      const fd = new FormData();
      fd.append('csrf', csrf); fd.append('id', btn.dataset.id);
      const r = await fetch('../api/ai.php', {method: 'POST', body: fd});
      const j = await r.json();
      if (j.ok) { msg.style.color = '#9ff0c6'; msg.textContent = '✅ آماده شد (' + j.model + ') — صفحه رفرش می‌شود…'; setTimeout(() => location.reload(), 900); }
      else { msg.style.color = '#ffb3bd'; msg.textContent = '⚠ ' + (j.error || 'خطا'); btn.disabled = false; btn.textContent = '⚡ تولید'; }
    } catch (e) { msg.style.color = '#ffb3bd'; msg.textContent = '⚠ خطای شبکه'; btn.disabled = false; btn.textContent = '⚡ تولید'; }
  });
});
</script>
<?php page_footer(); ?>
