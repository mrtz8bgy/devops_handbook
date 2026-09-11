/* DevOps Handbook v3 — اسکریپت مشترک */
(function () {
  'use strict';

  // پیام شناور
  window.toast = function (msg) {
    let t = document.querySelector('.toast');
    if (!t) { t = document.createElement('div'); t.className = 'toast'; document.body.appendChild(t); }
    t.textContent = msg;
    t.classList.add('show');
    clearTimeout(t._h);
    t._h = setTimeout(() => t.classList.remove('show'), 2200);
  };

  // کپی متن (با فال‌بک)
  window.copyText = async function (text, msg) {
    try { await navigator.clipboard.writeText(text); }
    catch (e) {
      const ta = document.createElement('textarea');
      ta.value = text; document.body.appendChild(ta);
      ta.select(); try { document.execCommand('copy'); } catch (_) {}
      ta.remove();
    }
    toast(msg || '✅ کپی شد!');
  };

  // جستجوی زنده: <input data-suggest="api-url"> + <div class="suggest-drop">
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('input[data-suggest]').forEach(inp => {
      const box = inp.closest('.search-hero, .search-box, form')?.parentElement
        ?.querySelector('.suggest-drop') || inp.parentElement.querySelector('.suggest-drop');
      const drop = box || (() => {
        const d = document.createElement('div');
        d.className = 'suggest-drop';
        inp.parentElement.style.position = 'relative';
        inp.parentElement.appendChild(d);
        return d;
      })();
      let timer = null;
      inp.addEventListener('input', () => {
        clearTimeout(timer);
        const q = inp.value.trim();
        if (q.length < 2) { drop.classList.remove('open'); drop.innerHTML = ''; return; }
        timer = setTimeout(async () => {
          try {
            const r = await fetch(inp.dataset.suggest + '?q=' + encodeURIComponent(q));
            const items = await r.json();
            if (!items.length) { drop.classList.remove('open'); drop.innerHTML = ''; return; }
            const detailBase = inp.dataset.detail || 'user/command_detail_readonly.php';
            drop.innerHTML = items.map(it =>
              `<a href="${detailBase}?id=${it.id}"><span>${escapeHtml((it.description || '').substring(0, 60))}</span><code>${escapeHtml(it.command)}</code></a>`
            ).join('');
            drop.classList.add('open');
          } catch (e) { /* ignore */ }
        }, 220);
      });
      document.addEventListener('click', e => {
        if (!drop.contains(e.target) && e.target !== inp) drop.classList.remove('open');
      });
      inp.addEventListener('keydown', e => { if (e.key === 'Escape') drop.classList.remove('open'); });
    });

    // تأیید حذف
    document.querySelectorAll('[data-confirm]').forEach(el => {
      el.addEventListener('click', e => {
        if (!confirm(el.dataset.confirm || 'مطمئنی؟')) e.preventDefault();
      });
    });
  });

  function escapeHtml(s) {
    return String(s ?? '').replace(/[&<>"]/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[m]));
  }
  window.escapeHtml = escapeHtml;
})();
