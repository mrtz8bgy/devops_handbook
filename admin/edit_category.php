<?php
require_once 'config.php';
require_admin($pdo);

$cat = isset($_GET['cat']) ? trim((string)$_GET['cat']) : '';
$success = '';
$error = '';

if ($cat === '') {
    header('Location: manage_categories.php');
    exit;
}

$check = $pdo->prepare("SELECT category FROM categories WHERE category = ?");
$check->execute([$cat]);
$category_exists = $check->fetchColumn();

if ($category_exists === false) {
    header('Location: manage_categories.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_category'])) {
    $new_name = trim((string)$_POST['category_name']);

    if ($new_name === '') {
        $error = '❌ نام دسته‌بندی نمی‌تواند خالی باشد.';
    } elseif ($new_name !== $cat) {
        $dup = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE category = ?");
        $dup->execute([$new_name]);
        if ((int)$dup->fetchColumn() > 0) {
            $error = "❌ دسته‌بندی '{$new_name}' قبلاً وجود دارد.";
        } else {
            try {
                $pdo->beginTransaction();
                $updCat = $pdo->prepare("UPDATE categories SET category = ? WHERE category = ?");
                $updCat->execute([$new_name, $cat]);

                $updCommands = $pdo->prepare("UPDATE commands SET category = ? WHERE category = ?");
                $updCommands->execute([$new_name, $cat]);

                $pdo->commit();
                $success = "✅ دسته‌بندی با موفقیت به '{$new_name}' تغییر کرد.";
                $cat = $new_name;
            } catch (Exception $e) {
                if ($pdo->inTransaction()) $pdo->rollBack();
                $error = '❌ خطا در ویرایش دسته‌بندی: ' . $e->getMessage();
            }
        }
    } else {
        $success = '✅ هیچ تغییری اعمال نشد.';
    }
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ویرایش دسته‌بندی</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../assets/css/luxury.css?v=3.1">
    <style>
        body { font-family: Tahoma, sans-serif; }
        .page-wrap { max-width: 760px; margin: 30px auto; }
        .panel {
            background: rgba(16, 22, 36, 0.9);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 18px;
            box-shadow: 0 18px 40px rgba(0,0,0,.25);
            overflow: hidden;
        }
        .panel-head {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: #1a1a1a;
            padding: 24px 28px;
        }
        .panel-head h2 { margin: 0; font-size: 1.6rem; }
        .panel-body { padding: 28px; }
        .field { margin-bottom: 18px; }
        .field label { display: block; margin-bottom: 8px; font-weight: 600; }
        .field input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,.12);
            background: rgba(10,16,28,.8);
            color: #fff;
            font-size: 1rem;
            font-family: inherit;
        }
        .btn-row { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 18px; }
        .btn {
            display: inline-block;
            text-decoration: none;
            border: none;
            border-radius: 12px;
            padding: 11px 18px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
        }
        .btn-primary { background: #22c55e; color: #06130d; }
        .btn-secondary { background: rgba(255,255,255,.08); color: #fff; }
        .alert { padding: 12px 14px; border-radius: 10px; margin-bottom: 16px; }
        .alert-success {
            background: rgba(34,197,94,.12);
            border: 1px solid rgba(34,197,94,.35);
            color: #baf5d1;
        }
        .alert-error {
            background: rgba(239,68,68,.12);
            border: 1px solid rgba(239,68,68,.35);
            color: #ffc7c7;
        }
    </style>
</head>
<body>
    <div class="page-wrap">
        <div class="panel">
            <div class="panel-head">
                <h2>✏️ ویرایش دسته‌بندی</h2>
            </div>
            <div class="panel-body">
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="field">
                        <label for="category_name">نام دسته‌بندی</label>
                        <input id="category_name" name="category_name" type="text" value="<?= htmlspecialchars($cat) ?>" required>
                    </div>

                    <div class="btn-row">
                        <button type="submit" name="update_category" class="btn btn-primary">💾 ذخیره تغییرات</button>
                        <a href="manage_categories.php" class="btn btn-secondary">↩️ بازگشت</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
