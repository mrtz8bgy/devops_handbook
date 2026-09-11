<?php
// ============================================================
// DevOps Handbook v3 — توابع کمکی مشترک
// ============================================================
if (defined('DH_HELPERS')) return;
define('DH_HELPERS', true);

/** خروجی امن HTML */
function esc($s) {
    return htmlspecialchars((string)($s ?? ''), ENT_QUOTES, 'UTF-8');
}

/** ارقام فارسی */
function fa_digits($s) {
    return str_replace(['0','1','2','3','4','5','6','7','8','9'],
                       ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'], (string)$s);
}

/** یکدست‌سازی متن فارسی/انگلیسی برای جستجو */
function fa_normalize($text) {
    $t = (string)$text;
    $t = str_replace(['ي', 'ك', '‌', ' '], ['ی', 'ک', ' ', ' '], $t); // ي/ك عربی + نیم‌فاصله
    $t = preg_replace('/[\x{064B}-\x{0652}]/u', '', $t); // اعراب
    $t = preg_replace('/[?؟!.,;:()\[\]{}"«»\-_+=\\\\|<>~`@#$%^&*\/]/u', ' ', $t);
    $t = preg_replace('/\s+/u', ' ', $t);
    return trim(mb_strtolower($t, 'UTF-8'));
}

/** توکنایز + حذف ایست‌واژه‌ها */
function fa_tokens($text, $min_len = 2) {
    static $stop = null;
    if ($stop === null) {
        $fa = 'و در به از که با را این آن ها های می شود شده است هستند بود برای یا اگر چه هم نه روی بین پس تا ولی اما یعنی دستور کامند چیه چیست چطور چگونه جوری راه روش من یه یک چند مورد درباره راجع کنم باشه هست نیست داره کمک توضیح بیشتر رو ام اش ای کجا کی چرا بده بگو نشون نشان کامل لیست همه توی داخل';
        $stop = [];
        foreach (preg_split('/\s+/u', $fa) as $w) $stop[$w] = true;
        // ایست‌واژه‌های انگلیسی
        foreach (['a','an','the','and','or','of','to','in','on','for','with','how','what','show','me','give','please','tell','command','cmd','is','are','was','were','be','do','does','did','can','could','should','would','will','i','you','it','we','they','my','your','this','that','these','those','as','at','by','from','into','about','like','all','any','more','most','get','list'] as $w) $stop[$w] = true;
    }
    $out = [];
    foreach (preg_split('/\s+/u', fa_normalize($text)) as $w) {
        $w = trim($w);
        if (mb_strlen($w, 'UTF-8') < $min_len) continue;
        if (isset($stop[$w])) continue;
        $out[] = $w;
    }
    return array_values(array_unique($out));
}

/** زمان نسبی فارسی */
function time_ago_fa($dt) {
    $ts = is_numeric($dt) ? (int)$dt : strtotime($dt);
    if (!$ts) return '';
    $diff = time() - $ts;
    if ($diff < 60) return 'لحظاتی پیش';
    if ($diff < 3600) return fa_digits(floor($diff / 60)) . ' دقیقه پیش';
    if ($diff < 86400) return fa_digits(floor($diff / 3600)) . ' ساعت پیش';
    if ($diff < 86400 * 30) return fa_digits(floor($diff / 86400)) . ' روز پیش';
    if ($diff < 86400 * 365) return fa_digits(floor($diff / (86400 * 30))) . ' ماه پیش';
    return fa_digits(floor($diff / (86400 * 365))) . ' سال پیش';
}

/** آیکون دسته‌بندی */
function category_icon($cat) {
    static $map = [
        'Linux' => '🐧', 'Docker' => '🐳', 'Git' => '📝', 'Kubernetes' => '☸️',
        'Network' => '🌐', 'Ansible' => '🤖', 'Jenkins' => '🔧', 'Python' => '🐍',
        'postgres' => '🐘', 'MongoDB' => '🍃', 'Nginx' => '⚡', 'PostgreSQL' => '🐘',
        'Redis' => '📀', 'Terraform' => '🏗️', 'Database' => '🗄️', 'GitLab' => '🦊',
        'Jira' => '📋', 'Nexus' => '📦', 'SSL' => '🔒',
    ];
    return $map[$cat] ?? '📁';
}

/** پیشوند مسیر نسبی به ریشه (برای فایل‌های داخل admin/ و user/ و api/) */
function base_prefix() {
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    if (preg_match('#/(admin|user|api)/[^/]*$#', $script)) return '../';
    return '';
}

/** آدرس فایل استاتیک */
function asset($path) {
    return base_prefix() . 'assets/' . ltrim($path, '/');
}

/** پاسخ JSON */
function json_response($data, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

/** ریدایرکت امن (فقط مسیر داخلی) */
function safe_redirect($url, $fallback = 'index.php') {
    if (!is_string($url) || $url === '' || preg_match('#^(https?:)?//#i', $url)) $url = $fallback;
    if ($url[0] !== '/' && !preg_match('#^[a-zA-Z0-9_\-./?=&%]+$#', $url)) $url = $fallback;
    header('Location: ' . $url);
    exit;
}

/** توکن CSRF */
function csrf_token() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_field() {
    return '<input type="hidden" name="csrf" value="' . esc(csrf_token()) . '">';
}
function csrf_verify() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $t = $_POST['csrf'] ?? '';
    return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], (string)$t);
}
