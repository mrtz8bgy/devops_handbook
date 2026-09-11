<?php
// ============================================
// جلوگیری از نمایش خطاها
// ============================================
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/error.log');

// ایجاد پوشه لاگ
if (!is_dir(__DIR__ . '/logs')) {
    mkdir(__DIR__ . '/logs', 0777, true);
}

// ============================================
// تنظیمات دیتابیس
// ============================================
$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME') ?: 'devops_handbook';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    die("خطا در اتصال به دیتابیس. لطفاً با مدیر سیستم تماس بگیرید.");
}

// تنظیم زمان
date_default_timezone_set('Asia/Tehran');

// هسته مشترک v3 (توابع کمکی، احراز هویت، موتور جستجو و چت‌بات)
require_once __DIR__ . '/includes/bootstrap.php';
?>