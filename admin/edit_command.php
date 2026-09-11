<?php
require_once 'config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$command = null;
$success = '';
$error = '';

// دریافت اطلاعات دستور
if($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM commands WHERE id = ?");
    $stmt->execute([$id]);
    $command = $stmt->fetch();
    
    if(!$command) {
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}

// پردازش فرم ویرایش
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_command'])) {
    $category = trim($_POST['category']);
    $cmd = trim($_POST['command']);
    $description = trim($_POST['description']);
    $keywords = trim($_POST['keywords']);
    $example = trim($_POST['example']);
    $similar = trim($_POST['similar_commands']);
    
    if(empty($category) || empty($cmd) || empty($description)) {
        $error = "❌ لطفاً حداقل دسته‌بندی، دستور و توضیحات را پر کنید!";
    } else {
        try {
            $sql = "UPDATE commands SET 
                    category = ?, 
                    command = ?, 
                    description = ?, 
                    keywords = ?, 
                    example = ?, 
                    similar_commands = ? 
                    WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            
            if($stmt->execute([$category, $cmd, $description, $keywords, $example, $similar, $id])) {
                $success = "✅ دستور با موفقیت ویرایش شد!";
                // بارگذاری مجدد اطلاعات
                $stmt = $pdo->prepare("SELECT * FROM commands WHERE id = ?");
                $stmt->execute([$id]);
                $command = $stmt->fetch();
            } else {
                $error = "❌ خطا در ویرایش دستور";
            }
        } catch(PDOException $e) {
            $error = "❌ خطا: " . $e->getMessage();
        }
    }
}

// دریافت لیست دسته‌بندی‌ها برای نمایش در انتخابگر
$categories = $pdo->query("SELECT category FROM categories ORDER BY category")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ویرایش دستور - <?php echo htmlspecialchars($command['command']); ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        .edit-container {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            margin-top: 30px;
        }
        
        .edit-header {
            background: linear-gradient(135deg, #ffc107, #ff9800);
            color: #333;
            padding: 30px;
            text-align: center;
        }
        
        .edit-header h1 {
            font-size: 2em;
            margin-bottom: 10px;
        }
        
        .edit-header p {
            opacity: 0.9;
        }
        
        .edit-body {
            padding: 40px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
            font-size: 14px;
        }
        
        .form-group label i {
            margin-left: 5px;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
            font-family: inherit;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #ff9800;
            box-shadow: 0 0 0 3px rgba(255,152,0,0.1);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .help-text {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
        }
        
        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .btn-update {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 12px 35px;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s;
        }
        
        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40,167,69,0.3);
        }
        
        .btn-cancel {
            background: #6c757d;
            color: white;
            padding: 12px 35px;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }
        
        .btn-cancel:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
        
        .btn-delete {
            background: #dc3545;
            color: white;
            padding: 12px 35px;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }
        
        .btn-delete:hover {
            background: #c82333;
            transform: translateY(-2px);
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-right: 4px solid #28a745;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-right: 4px solid #dc3545;
        }
        
        .command-preview {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
        }
        
        .command-preview h4 {
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .command-preview code {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 10px;
            border-radius: 8px;
            display: block;
            font-family: monospace;
        }
        
        @media (max-width: 768px) {
            .edit-body {
                padding: 20px;
            }
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            .button-group {
                flex-direction: column;
            }
            .button-group a, .button-group button {
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="edit-container">
            <div class="edit-header">
                <h1>✏️ ویرایش دستور</h1>
                <p>در حال ویرایش: <?php echo htmlspecialchars($command['command']); ?></p>
            </div>
            
            <div class="edit-body">
                <?php if($success): ?>
                    <div class="alert-success">
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>
                
                <?php if($error): ?>
                    <div class="alert-error">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <div class="command-preview">
                    <h4>🔍 دستور در حال ویرایش:</h4>
                    <code>$ <?php echo htmlspecialchars($command['command']); ?></code>
                </div>
                
                <form method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label>📁 دسته‌بندی:</label>
                            <select name="category" required>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?php echo htmlspecialchars($cat['category']); ?>" 
                                        <?php echo ($cat['category'] == $command['category']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['category']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="help-text">دسته‌بندی این دستور را انتخاب کنید</div>
                        </div>
                        
                        <div class="form-group">
                            <label>💻 دستور:</label>
                            <input type="text" name="command" required 
                                   value="<?php echo htmlspecialchars($command['command']); ?>"
                                   placeholder="مثال: docker ps -a">
                            <div class="help-text">نام دستور اصلی که میخواهید ذخیره شود</div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>📖 توضیحات:</label>
                        <textarea name="description" required placeholder="توضیح کامل دستور..."><?php echo htmlspecialchars($command['description']); ?></textarea>
                        <div class="help-text">توضیح دقیق اینکه این دستور چه کاری انجام میدهد</div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>🔑 کلمات کلیدی:</label>
                            <input type="text" name="keywords" 
                                   value="<?php echo htmlspecialchars($command['keywords']); ?>"
                                   placeholder="مثال: container, list, docker">
                            <div class="help-text">با کاما جدا کنید - برای جستجوی بهتر</div>
                        </div>
                        
                        <div class="form-group">
                            <label>🔄 دستورات مشابه:</label>
                            <input type="text" name="similar_commands" 
                                   value="<?php echo htmlspecialchars($command['similar_commands']); ?>"
                                   placeholder="مثال: docker ps -a, docker stats">
                            <div class="help-text">با کاما جدا کنید - دستوراتی که کار مشابه دارند</div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>💡 مثال کاربردی:</label>
                        <textarea name="example" placeholder="مثال استفاده از دستور..."><?php echo htmlspecialchars($command['example']); ?></textarea>
                        <div class="help-text">یک مثال واقعی از نحوه استفاده این دستور</div>
                    </div>
                    
                    <div class="button-group">
                        <button type="submit" name="update_command" class="btn-update">
                            💾 ذخیره تغییرات
                        </button>
                        <a href="command_detail.php?id=<?php echo $command['id']; ?>" class="btn-cancel">
                            🔙 بازگشت به جزئیات
                        </a>
                        <a href="delete_command.php?id=<?php echo $command['id']; ?>" 
                           onclick="return confirm('آیا از حذف دستور <?php echo $command['command']; ?> مطمئن هستید؟')" 
                           class="btn-delete">
                            🗑️ حذف دستور
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // نمایش پیش‌نمایش زنده دستور (اختیاری)
        const commandInput = document.querySelector('input[name="command"]');
        const previewBox = document.querySelector('.command-preview code');
        
        if(commandInput && previewBox) {
            commandInput.addEventListener('input', function() {
                previewBox.textContent = '$ ' + this.value;
            });
        }
    </script>
</body>
</html>