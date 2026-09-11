<?php
require_once 'config.php';
require_admin($pdo); // v3: فقط ادمین

// افزودن دسته‌بندی جدید
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $new_category = trim($_POST['new_category']);
    if(!empty($new_category)) {
        // بررسی تکراری نبودن
        $check = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE category = ?");
        $check->execute([$new_category]);
        if($check->fetchColumn() == 0) {
            $stmt = $pdo->prepare("INSERT INTO categories (category) VALUES (?)");
            if($stmt->execute([$new_category])) {
                $success = "✅ دسته‌بندی '$new_category' با موفقیت اضافه شد!";
            } else {
                $error = "❌ خطا در افزودن دسته‌بندی";
            }
        } else {
            $error = "❌ این دسته‌بندی قبلاً وجود دارد!";
        }
    }
}

// حذف دسته‌بندی
if(isset($_GET['delete'])) {
    $category = $_GET['delete'];
    // بررسی وجود دستور در این دسته‌بندی
    $check = $pdo->prepare("SELECT COUNT(*) FROM commands WHERE category = ?");
    $check->execute([$category]);
    if($check->fetchColumn() > 0) {
        $error = "❌ نمی‌توان دسته‌بندی را حذف کرد! تعداد " . $check->fetchColumn() . " دستور در این دسته وجود دارد.";
    } else {
        $stmt = $pdo->prepare("DELETE FROM categories WHERE category = ?");
        if($stmt->execute([$category])) {
            $success = "✅ دسته‌بندی '$category' حذف شد!";
        } else {
            $error = "❌ خطا در حذف دسته‌بندی";
        }
    }
}

// دریافت لیست دسته‌بندی‌ها
$categories = $pdo->query("SELECT * FROM categories ORDER BY category")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدیریت دسته‌بندی‌ها</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../assets/css/luxury.css?v=3.1">
    <style>
        .categories-list {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
        }
        .category-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
            transition: background 0.3s;
        }
        .category-item:hover {
            background: #f9f9f9;
        }
        .category-name {
            font-size: 1.1em;
            font-weight: bold;
            color: #667eea;
        }
        .category-count {
            background: #667eea;
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            margin-right: 10px;
        }
        .delete-cat {
            background: #dc3545;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .delete-cat:hover {
            background: #c82333;
            transform: scale(1.05);
        }
        .edit-cat {
            background: #ffc107;
            color: #333;
            border: none;
            padding: 5px 15px;
            border-radius: 20px;
            cursor: pointer;
            margin-right: 10px;
            transition: all 0.3s;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏷️ مدیریت دسته‌بندی‌ها</h1>
            <div class="nav-menu">
                <a href="index.php" class="nav-btn">🏠 صفحه اصلی</a>
                <a href="add.php" class="nav-btn">➕ افزودن دستور</a>
                <a href="manage_categories.php" class="nav-btn">🏷️ مدیریت دسته‌بندی</a>
                <a href="users.php" class="nav-btn">👥 کاربران</a>
                <a href="change_password.php" class="nav-btn">🔑 رمز عبور</a>
                <a href="../logout.php" class="nav-btn">🚪 خروج</a>
            </div>
        </div>

        <div class="form-container">
            <h2>➕ افزودن دسته‌بندی جدید</h2>
            <?php if(isset($success)): ?>
                <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <?php if(isset($error)): ?>
                <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>نام دسته‌بندی جدید:</label>
                    <input type="text" name="new_category" required placeholder="مثال: Ansible, Jenkins, Kubernetes">
                </div>
                <button type="submit" name="add_category" class="submit-btn">➕ افزودن دسته‌بندی</button>
            </form>
        </div>

        <div class="categories-list">
            <h2>📋 لیست دسته‌بندی‌های موجود</h2>
            <?php foreach($categories as $cat): 
                $count = $pdo->prepare("SELECT COUNT(*) FROM commands WHERE category = ?");
                $count->execute([$cat['category']]);
                $cmd_count = $count->fetchColumn();
            ?>
                <div class="category-item">
                    <div>
                        <span class="category-name"><?php echo htmlspecialchars($cat['category']); ?></span>
                        <span class="category-count"><?php echo $cmd_count; ?> دستور</span>
                    </div>
                    <div>
                        <a href="edit_category.php?cat=<?php echo urlencode($cat['category']); ?>" class="edit-cat">✏️ ویرایش</a>
                        <?php if($cmd_count == 0): ?>
                            <a href="?delete=<?php echo urlencode($cat['category']); ?>" 
                               onclick="return confirm('آیا از حذف دسته‌بندی <?php echo $cat['category']; ?> مطمئن هستید؟')" 
                               class="delete-cat">🗑️ حذف</a>
                        <?php else: ?>
                            <button class="delete-cat" disabled style="background: #ccc;" title="ابتدا دستورات این دسته را حذف کنید">🔒 غیرقابل حذف</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>