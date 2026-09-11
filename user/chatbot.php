<?php
require_once 'config.php';
require_once __DIR__ . '/../includes/layout.php';

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

page_head('چت‌بات هوشمند', 'دستیار فارسی DevOps: بپرس، دستور بگیر');
topbar($pdo, user_links('chat'));
?>
<div class="container">
  <div class="chat-shell">
    <div class="chat-head">
      <a href="../index.php" class="chat-home">🏠 خانه</a>
      <h2>🤖 دستیار هوشمند <span>DevOps</span></h2>
      <p>فارسی بپرس، دستور بگیر — هر روز باهوش‌تر می‌شم ✨</p>
    </div>
    <div class="chat-cats-bar" id="catsBar"><button disabled>⏳ بارگذاری دسته‌ها...</button></div>
    <div class="chat-body" id="chatBody">
      <div class="msg bot">
        👋 سلام! من دستیار DevOps هستم.<br>
        • بنویس <b>«دستورات داکر»</b> تا همه دستورات یه دسته رو ببینی<br>
        • بنویس <b>«لاگ»</b> یا <b>«بکاپ»</b> تا جستجو کنم<br>
        • بنویس <b>«راهنما»</b> تا بیشتر بدونی
      </div>
    </div>
    <div class="chat-suggest" id="chatSuggest"></div>
    <form class="chat-foot" id="chatForm">
      <input type="text" id="q" placeholder="مثلاً: لاگ داکر، دستورات لینوکس..." autocomplete="off" maxlength="500">
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
  d.className = 'msg ' + cls;
  d.innerHTML = html;
  chatBody.appendChild(d);
  chatBody.scrollTop = chatBody.scrollHeight;
  return d;
}
function typing() {
  return addMsg('<span class="typing"><i></i><i></i><i></i></span>', 'bot');
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
setSuggest(['دستورات داکر', 'لاگ لینوکس', 'راهنما']);

chatForm.addEventListener('submit', async (e) => {
  e.preventDefault();
  const q = qInput.value.trim();
  if (!q) return;
  addMsg(escapeHtml(q), 'user');
  qInput.value = '';
  const tp = typing();
  try {
    const r = await fetch('chatbot.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
      body: 'question=' + encodeURIComponent(q)
    });
    const data = await r.json();
    tp.remove();
    let html = data.answer || 'پاسخی دریافت نشد.';
    (data.commands || []).forEach(c => {
      const desc = escapeHtml((c.description || '').substring(0, 90));
      html += `<div class="chat-card-item">
        <span class="t">${escapeHtml(c.command)}</span><span class="d">${desc}</span>
        <span class="b">
          <button class="mini-btn mini-copy" data-cmd="${escapeHtml(c.command)}">📋 کپی</button>
          <a class="mini-btn mini-detail" target="_blank" href="command_detail_readonly.php?id=${c.id}">🔍 جزئیات</a>
        </span></div>`;
    });
    if (data.qa_id) {
      html += `<div class="feedback-row">مفید بود؟
        <button class="fb-good" data-fb="excellent" data-qa="${data.qa_id}">👍 بله</button>
        <button class="fb-bad" data-fb="bad" data-qa="${data.qa_id}">👎 نه</button></div>`;
    }
    const el = addMsg(html, 'bot');
    el.querySelectorAll('[data-cmd]').forEach(b => b.onclick = () => copyText(b.dataset.cmd, '✅ دستور کپی شد!'));
    el.querySelectorAll('.chat-cat').forEach(b => b.onclick = () => { qInput.value = b.dataset.q; chatForm.requestSubmit(); });
    el.querySelectorAll('[data-fb]').forEach(b => b.onclick = async () => {
      await fetch('chatbot.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
        body: 'qa_id=' + b.dataset.qa + '&feedback=' + b.dataset.fb
      });
      el.querySelectorAll('[data-fb]').forEach(x => x.disabled = true);
      toast('🙏 ممنون از بازخوردت!');
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
