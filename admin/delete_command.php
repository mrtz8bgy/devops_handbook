<?php
require_once 'config.php';
require_admin($pdo); // v3: فقط ادمین

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$command = null;
$deleted = false;
$error = '';

// دریافت اطلاعات دستور قبل از حذف
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

// پردازش حذف
if(isset($_POST['confirm_delete']) && $_POST['confirm_delete'] == 'yes') {
    try {
        $stmt = $pdo->prepare("DELETE FROM commands WHERE id = ?");
        if($stmt->execute([$id])) {
            $deleted = true;
            
            // redirect after 2 seconds
            header("refresh:2;url=index.php");
        } else {
            $error = "❌ خطا در حذف دستور!";
        }
    } catch(PDOException $e) {
        $error = "❌ خطا: " . $e->getMessage();
    }
}

// برگشت به صفحه قبل
if(isset($_POST['cancel'])) {
    header("Location: command_detail.php?id=" . $id);
    exit;
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حذف دستور - <?php echo htmlspecialchars($command['command']); ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../assets/css/luxury.css?v=3.1">
    <style>
        .delete-container {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            margin-top: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .delete-header {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .delete-header h1 {
            font-size: 2em;
            margin-bottom: 10px;
        }
        
        .delete-header .warning-icon {
            font-size: 60px;
            margin-bottom: 15px;
        }
        
        .delete-body {
            padding: 40px;
            text-align: center;
        }
        
        .command-info {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
            text-align: right;
        }
        
        .command-info h3 {
            color: #dc3545;
            margin-bottom: 15px;
            font-size: 1.3em;
        }
        
        .command-detail {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 12px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            margin: 10px 0;
        }
        
        .warning-text {
            background: #fff3cd;
            border-right: 4px solid #ffc107;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
            color: #856404;
        }
        
        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .btn-confirm {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            padding: 12px 35px;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s;
        }
        
        .btn-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220,53,69,0.3);
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
        
        .success-container {
            text-align: center;
            padding: 40px;
        }
        
        .success-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 20px;
            border-radius: 15px;
            margin: 20px 0;
        }
        
        .redirect-message {
            color: #666;
            font-size: 14px;
            margin-top: 20px;
        }
        
        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
            margin-right: 10px;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        @media (max-width: 768px) {
            .delete-body {
                padding: 20px;
            }
            .button-group {
                flex-direction: column;
            }
            .btn-confirm, .btn-cancel {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if(!$deleted): ?>
            <div class="delete-container">
                <div class="delete-header">
                    <div class="warning-icon">⚠️</div>
                    <h1>حذف دستور</h1>
                    <p>آیا از حذف این دستور اطمینان دارید؟</p>
                </div>
                
                <div class="delete-body">
                    <?php if($error): ?>
                        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="command-info">
                        <h3>📋 اطلاعات دستور</h3>
                        <div class="command-detail">
                            <strong>دستور:</strong> $ <?php echo htmlspecialchars($command['command']); ?>
                        </div>
                        <div class="command-detail" style="background: #f0f0f0; color: #333; margin-top: 10px;">
                            <strong>دسته‌بندی:</strong> <?php echo htmlspecialchars($command['category']); ?>
                        </div>
                        <?php if($command['description']): ?>
                            <div style="margin-top: 10px; padding: 10px; background: #e9ecef; border-radius: 8px;">
                                <strong>توضیحات:</strong><br>
                                <?php echo htmlspecialchars(mb_substr($command['description'], 0, 150)); ?>
                                <?php if(mb_strlen($command['description']) > 150) echo '...'; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="warning-text">
                        <strong>⚠️ توجه:</strong><br>
                        این عمل غیرقابل بازگشت است! دستور حذف شده قابل بازیابی نخواهد بود.
                    </div>
                    
                    <form method="POST">
                        <div class="button-group">
                            <button type="submit" name="confirm_delete" value="yes" class="btn-confirm">
                                🗑️ بله، حذف شود
                            </button>
                            <button type="submit" name="cancel" class="btn-cancel">
                                🔙 انصراف
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="delete-container">
                <div class="success-container">
                    <div class="success-icon">✅</div>
                    <div class="success-message">
                        <h3>دستور با موفقیت حذف شد!</h3>
                        <p>دستور "<?php echo htmlspecialchars($command['command']); ?>" از کتابخانه حذف شد.</p>
                    </div>
                    <div class="redirect-message">
                        <div class="spinner"></div>
                        در حال انتقال به صفحه اصلی...
                    </div>
                    <a href="index.php" style="display: inline-block; margin-top: 20px; color: #667eea;">کلیک کنید اگر به طور خودکار منتقل نشدید</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>