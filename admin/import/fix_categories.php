<?php
require_once 'config.php';

// بررسی دسته‌بندی‌های اشتباه
$stmt = $pdo->query("SELECT DISTINCT category FROM commands WHERE category LIKE '%\"%' OR category LIKE '%//%'");
$bad_categories = $stmt->fetchAll();

foreach($bad_categories as $bad) {
    $bad_cat = $bad['category'];
    // استخراج نام واقعی دسته‌بندی
    $clean_cat = trim($bad_cat, '" \'');
    $clean_cat = preg_replace('/\s*\/\/.*$/', '', $clean_cat);
    
    // اطمینان از وجود دسته‌بندی تمیز
    $check = $pdo->prepare("INSERT IGNORE INTO categories (category) VALUES (?)");
    $check->execute([$clean_cat]);
    
    // آپدیت دستورات
    $update = $pdo->prepare("UPDATE commands SET category = ? WHERE category = ?");
    $update->execute([$clean_cat, $bad_cat]);
    
    echo "✅ دسته‌بندی '$bad_cat' به '$clean_cat' تغییر یافت<br>";
}

// حذف دسته‌بندی‌های خالی و اشتباه
$pdo->exec("DELETE FROM categories WHERE category LIKE '%\"%' OR category LIKE '%//%'");

echo "<br>🎉 عملیات پاکسازی با موفقیت انجام شد!";