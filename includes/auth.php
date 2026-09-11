<?php
// ============================================================
// DevOps Handbook v3 — احراز هویت و سطح دسترسی
// ============================================================
if (defined('DH_AUTH')) return;
define('DH_AUTH', true);

function ensure_session() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['chat_session_id'])) {
        $_SESSION['chat_session_id'] = session_id() . '_' . time();
    }
}

/** کاربر فعلی یا null */
function current_user($pdo) {
    ensure_session();
    if (empty($_SESSION['uid'])) return null;
    try {
        $st = $pdo->prepare("SELECT id, username, role, created_at FROM users WHERE id = ?");
        $st->execute([(int)$_SESSION['uid']]);
        $u = $st->fetch(PDO::FETCH_ASSOC);
        return $u ?: null;
    } catch (Exception $e) {
        return null; // جدول users هنوز ساخته نشده
    }
}

function is_admin($pdo) {
    $u = current_user($pdo);
    return $u && $u['role'] === 'admin';
}

/** نیازمند ورود — در غیر این صورت به لاگین می‌رود */
function require_login($pdo) {
    if (!current_user($pdo)) {
        $next = $_SERVER['REQUEST_URI'] ?? '';
        safe_redirect(base_prefix() . 'login.php?next=' . urlencode($next), base_prefix() . 'login.php');
    }
}

/** نیازمند ادمین */
function require_admin($pdo) {
    $u = current_user($pdo);
    if (!$u) {
        $next = $_SERVER['REQUEST_URI'] ?? '';
        safe_redirect(base_prefix() . 'login.php?next=' . urlencode($next), base_prefix() . 'login.php');
    }
    if ($u['role'] !== 'admin') {
        http_response_code(403);
        die('⛔ دسترسی غیرمجاز — این بخش مخصوص مدیران است. <a href="' . esc(base_prefix()) . 'index.php">بازگشت</a>');
    }
}

/** تلاش برای ورود — موفق: آرایه کاربر، ناموفق: null */
function attempt_login($pdo, $username, $password) {
    ensure_session();
    $st = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $st->execute([trim($username)]);
    $u = $st->fetch(PDO::FETCH_ASSOC);
    if ($u && password_verify((string)$password, $u['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['uid'] = (int)$u['id'];
        $_SESSION['role'] = $u['role'];
        return $u;
    }
    return null;
}

function logout_user() {
    ensure_session();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}
