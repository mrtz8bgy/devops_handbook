<?php
// ============================================================
// DevOps Handbook v3 — قالب مشترک صفحات
// ============================================================
if (defined('DH_LAYOUT')) return;
define('DH_LAYOUT', true);
require_once __DIR__ . '/bootstrap.php';

/** شروع صفحه */
function page_head($title, $desc = '') {
    echo '<!DOCTYPE html><html lang="fa" dir="rtl"><head><meta charset="UTF-8">' .
        '<meta name="viewport" content="width=device-width, initial-scale=1.0">' .
        '<title>' . esc($title) . ' | کتابخانه DevOps</title>' .
        ($desc ? '<meta name="description" content="' . esc($desc) . '">' : '') .
        '<link rel="icon" href="' . asset('favicon.svg') . '" type="image/svg+xml">' .
        '<link rel="stylesheet" href="' . asset('css/luxury.css') . '">' .
        '</head><body>';
}

/** نوبار — $links: [['url'=>, 'label'=>, 'on'=>bool]] */
function topbar($pdo, $links = []) {
    $me = null;
    try { $me = current_user($pdo); } catch (Exception $e) {}
    $bp = base_prefix();
    echo '<div class="topbar"><div class="topbar-inner">';
    echo '<a href="' . $bp . 'index.php" class="brand"><span class="brand-badge">⌘</span><span>کتابخانه <b>DevOps</b></span></a>';
    echo '<div class="topbar-links">';
    foreach ($links as $l) {
        echo '<a href="' . esc($l['url']) . '"' . (!empty($l['on']) ? ' class="on"' : '') . '>' . $l['label'] . '</a>';
    }
    if ($me && $me['role'] === 'admin') echo '<a href="' . $bp . 'admin/index.php">🛠 ادمین</a>';
    if ($me) {
        echo '<span class="user-chip">👤 ' . esc($me['username']) . '</span>';
        echo '<a href="' . $bp . 'logout.php">🚪 خروج</a>';
    } else {
        echo '<a href="' . $bp . 'login.php">🔐 ورود</a>';
    }
    echo '</div></div></div>';
}

/** لینک‌های استاندارد بخش کاربر */
function user_links($active = '') {
    $bp = base_prefix();
    // اگر در ریشه هستیم، مسیرها باید user/… باشند
    $u = ($bp === '') ? 'user/' : '';
    return [
        ['url' => $bp . 'index.php', 'label' => '🏠 خانه', 'on' => $active === 'home'],
        ['url' => $u . 'index_readonly.php', 'label' => '📚 دستورات', 'on' => $active === 'browse'],
        ['url' => $u . 'search_readonly.php', 'label' => '🔍 جستجو', 'on' => $active === 'search'],
        ['url' => $u . 'chatbot.php', 'label' => '🤖 چت‌بات', 'on' => $active === 'chat'],
    ];
}

/** لینک‌های استاندارد بخش ادمین */
function admin_links($active = '') {
    return [
        ['url' => 'index.php', 'label' => '🏠 داشبورد', 'on' => $active === 'dash'],
        ['url' => 'add.php', 'label' => '➕ افزودن', 'on' => $active === 'add'],
        ['url' => 'search.php', 'label' => '🔍 جستجو', 'on' => $active === 'search'],
        ['url' => 'manage_categories.php', 'label' => '🏷️ دسته‌ها', 'on' => $active === 'cats'],
        ['url' => 'chatbot.php', 'label' => '🤖 چت‌بات', 'on' => $active === 'chat'],
        ['url' => 'manage_qa.php', 'label' => '📋 مدیریت QA', 'on' => $active === 'qa'],
        ['url' => 'manage_unknown.php', 'label' => '❓ بی‌جواب‌ها', 'on' => $active === 'unknown'],
        ['url' => 'users.php', 'label' => '👥 کاربران', 'on' => $active === 'users'],
        ['url' => 'change_password.php', 'label' => '🔑 رمز عبور', 'on' => $active === 'password'],
    ];
}

/** پایان صفحه */
function page_footer() {
    echo '<div class="footer"><div class="footer-inner">' .
        '<div><b>کتابخانه DevOps</b> — مرجع سریع دستورات متخصصان 🇮🇷</div>' .
        '<div>نسخه ' . DH_VERSION . ' ✦ سرمه‌ای ✦ طلایی</div>' .
        '</div></div>' .
        '<script src="' . asset('js/app.js') . '"></script></body></html>';
}

/** نیازمند bootstrap */
if (!defined('DH_BOOT')) {
    $try = [__DIR__ . '/bootstrap.php', __DIR__ . '/../includes/bootstrap.php'];
}
