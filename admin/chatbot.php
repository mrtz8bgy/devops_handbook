<?php
require_once 'config.php';
require_once __DIR__ . '/../includes/layout.php';
require_admin($pdo);

// پاسخ AJAX
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    if (isset($_POST['question'])) {
        $me = current_user($pdo);
        $resp = chatbot_answer($pdo, $_POST['question'], ['user_id' => $me['id'] ?? null]);
        json_response($resp);
    }
    if (isset($_POST['feedback'], $_POST['qa_id'])) {
        $ok = save_feedback($pdo, (int)$_POST['qa_id'], $_SESSION['chat_session_id'] ?? session_id(), $_POST['feedback']);
        json_response(['ok' => $ok]);
    }
}

try { $pending = (int)$pdo->query("SELECT COUNT(*) FROM chatbot_unknown_questions WHERE status = 'pending'")->fetchColumn(); }
catch (Exception $e) { $pending = 0; }

page_head('تست چت‌بات');
topbar($pdo, admin_links('chat'));
?>
<div class="container">
  <div class="chat-shell">
    <div class="chat-head">
      <a href="index.php" class="chat-home">🏠 داشبورد</a>
      <h2>🤖 تست دستیار <span>DevOps</span></h2>
      <p>سؤال‌های بی‌جواب خودکار ثبت می‌شن — <a href="manage_unknown.php" style="color:var(--gold-light)">مشاهده (<?= fa_digits($pending) ?>)</a></p>
    </div>
    <div class="chat-cats-bar" id="catsBar"><button disabled>⏳ بارگذاری دسته‌ها...</button></div>
    <div class="chat-body" id="chatBody">
      <div class="msg bot">👋 سلام مدیر! اینجا می‌تونی موتور چت‌بات رو تست کنی.<br>سؤال‌های بی‌جواب کاربران میان توی بخش «بی‌جواب‌ها» تا جوابشون رو اضافه کنی.</div>
    </div>
    <div class="chat-suggest" id="chatSuggest"></div>
    <form class="chat-foot" id="chatForm">
      <input type="text" id="q" placeholder="سؤالت رو بنویس..." autocomplete="off" maxlength="500">
      <button type="submit">📨 ارسال</button>
    </form>
  </div>
</div>

<script>
const chatBody = document.getElementById('chatBody');
const chatSuggest = document.getElementById('chatSuggest');
const chatForm = document.getElementById('chatForm');
const qInput = document.getElementById('q');
function addMsg(html, cls) {
  const d = document.createElement('div');
  d.className = 'msg ' + cls; d.innerHTML = html;
  chatBody.appendChild(d); chatBody.scrollTop = chatBody.scrollHeight;
  return d;
}
function setSuggest(list) {
  chatSuggest.innerHTML = '';
  (list || []).forEach(s => {
    const b = document.createElement('button');
    b.type = 'button'; b.textContent = s;
    b.onclick = () => { qInput.value = s; chatForm.requestSubmit(); };
    chatSuggest.appendChild(b);
  });
}
chatForm.addEventListener('submit', async (e) => {
  e.preventDefault();
  const q = qInput.value.trim();
  if (!q) return;
  addMsg(escapeHtml(q), 'user');
  qInput.value = '';
  const tp = addMsg('<span class="typing"><i></i><i></i><i></i></span>', 'bot');
  try {
    const r = await fetch('chatbot.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
      body: 'question=' + encodeURIComponent(q)
    });
    const data = await r.json();
    tp.remove();
    let html = (data.answer || 'پاسخی دریافت نشد.') + (data.intent ? ` <small style="color:var(--muted)">[${escapeHtml(data.intent)}]</small>` : '');
    (data.commands || []).forEach(c => {
      html += `<div class="chat-card-item">
        <span class="t">${escapeHtml(c.command)}</span><span class="d">${escapeHtml((c.description || '').substring(0, 90))}</span>
        <span class="b">
          <button class="mini-btn mini-copy" data-cmd="${escapeHtml(c.command)}">📋 کپی</button>
          <a class="mini-btn mini-detail" target="_blank" href="command_detail.php?id=${c.id}">🔍 جزئیات</a>
        </span></div>`;
    });
    if (data.qa_id) {
      html += `<div class="feedback-row">مفید بود؟
        <button class="fb-good" data-fb="excellent" data-qa="${data.qa_id}">👍 بله</button>
        <button class="fb-bad" data-fb="bad" data-qa="${data.qa_id}">👎 نه</button></div>`;
    }
    const el = addMsg(html, 'bot');
    el.querySelectorAll('[data-cmd]').forEach(b => b.onclick = () => copyText(b.dataset.cmd, '✅ کپی شد!'));
    el.querySelectorAll('.chat-cat').forEach(b => b.onclick = () => { qInput.value = b.dataset.q; chatForm.requestSubmit(); });
    el.querySelectorAll('[data-fb]').forEach(b => b.onclick = async () => {
      await fetch('chatbot.php', { method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
        body: 'qa_id=' + b.dataset.qa + '&feedback=' + b.dataset.fb });
      el.querySelectorAll('[data-fb]').forEach(x => x.disabled = true);
      toast('🙏 ثبت شد!');
    });
    setSuggest(data.suggestions);
  } catch (err) { tp.remove(); addMsg('⚠️ خطا در ارتباط با سرور', 'bot'); }
});
(async function loadCats() {
  try {
    const r = await fetch('../get_categories.php');
    const cats = await r.json();
    const bar = document.getElementById('catsBar');
    bar.innerHTML = '';
    cats.forEach(c => {
      const b = document.createElement('button');
      b.textContent = c;
      b.onclick = () => { qInput.value = 'دستورات ' + c; chatForm.requestSubmit(); };
      bar.appendChild(b);
    });
  } catch (e) { document.getElementById('catsBar').innerHTML = ''; }
})();
</script>
<?php page_footer(); ?>
