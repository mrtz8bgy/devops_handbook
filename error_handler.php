<?php
// ============================================
// مدیریت خطاهای سفارشی
// ============================================

// تابع مدیریت خطاهای معمولی
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    // خطاهای Notice و Warning را نادیده بگیر (فقط لاگ کن)
    if (in_array($errno, [E_NOTICE, E_WARNING, E_USER_NOTICE, E_USER_WARNING])) {
        error_log("Warning/Notice: $errstr in $errfile on line $errline");
        return true;
    }
    
    // خطاهای جدی را ذخیره کن
    $error_message = "Error [$errno]: $errstr in $errfile on line $errline";
    error_log($error_message);
    
    // اگر در محیط production هستیم، پیام عمومی نشان بده
    if (!isset($_SERVER['HTTP_HOST']) || strpos($_SERVER['HTTP_HOST'], 'localhost') === false) {
        die("خطایی رخ داده است. لطفاً بعداً تلاش کنید.");
    }
    
    return false;
}

// تابع مدیریت خطاهای fatal
function shutdownHandler() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        error_log("Fatal Error: {$error['message']} in {$error['file']} on line {$error['line']}");
        
        if (!isset($_SERVER['HTTP_HOST']) || strpos($_SERVER['HTTP_HOST'], 'localhost') === false) {
            echo "<h3>خطای داخلی سرور</h3><p>لطفاً بعداً تلاش کنید.</p>";
        } else {
            echo "<h3>خطای fatal:</h3>";
            echo "<pre>{$error['message']} in {$error['file']} on line {$error['line']}</pre>";
        }
    }
}

// تابع مدیریت خطاهای استثنا (Exception)
function exceptionHandler($exception) {
    error_log("Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine());
    
    if (!isset($_SERVER['HTTP_HOST']) || strpos($_SERVER['HTTP_HOST'], 'localhost') === false) {
        die("خطایی رخ داده است. لطفاً بعداً تلاش کنید.");
    } else {
        die("<pre>Exception: " . htmlspecialchars($exception->getMessage()) . " in " . $exception->getFile() . " on line " . $exception->getLine() . "</pre>");
    }
}

// تنظیم کردن هندلرها
set_error_handler('customErrorHandler');
set_exception_handler('exceptionHandler');
register_shutdown_function('shutdownHandler');
?>