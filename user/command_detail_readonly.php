<?php
require_once 'config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$command = null;

if($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM commands WHERE id = ?");
    $stmt->execute([$id]);
    $command = $stmt->fetch();
    
    if(!$command) {
        header("Location: index_readonly.php");
        exit;
    }
}

$stmt_files = $pdo->prepare("SELECT * FROM command_files WHERE command_id = ? ORDER BY uploaded_at DESC");
$stmt_files->execute([$id]);
$attached_files = $stmt_files->fetchAll();
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($command['command']); ?> - راهنمای دستورات</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .detail-container {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            margin-top: 30px;
            position: relative;
        }
        
        .detail-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 40px;
            text-align: center;
            position: relative;
        }
        
        .readonly-badge-header {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #28a745;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
        }
        
        .detail-header h1 {
            font-size: 2.5em;
            margin-bottom: 15px;
            font-family: monospace;
        }
        
        .detail-category {
            display: inline-block;
            background: rgba(255,255,255,0.3);
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 14px;
        }
        
        .detail-copy-btn {
            background: rgba(255,255,255,0.25);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 15px;
        }
        
        .command-select-box {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 10px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .command-select-box:hover {
            background: #1e1e1e;
        }
        
        .detail-body {
            padding: 40px;
        }
        
        .info-section {
            margin-bottom: 35px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 25px;
        }
        
        .info-section h3 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 1.5em;
        }
        
        .keyword-list {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }
        
        .keyword-badge {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 8px 18px;
            border-radius: 25px;
            font-size: 14px;
        }
        
        .similar-commands {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 15px;
        }
        
        .similar-command {
            background: #f8f9fa;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            color: #667eea;
            font-weight: bold;
            transition: all 0.3s;
            border: 1px solid #e0e0e0;
        }
        
        .similar-command:hover {
            background: #667eea;
            color: white;
        }
        
        .file-item {
            background: #f8f9fa;
            padding: 12px 20px;
            border-radius: 12px;
            text-decoration: none;
            color: #667eea;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: 1px solid #e0e0e0;
        }
        
        .file-item:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        
        .back-btn {
            background: #6c757d;
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }
        
        .back-btn:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
        
        .copy-success-toast {
            position: fixed;
            bottom: 100px;
            right: 30px;
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 15px 25px;
            border-radius: 12px;
            z-index: 1000;
            animation: slideInRight 0.3s ease, fadeOutUp 0.5s ease 2s forwards;
        }
        
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes fadeOutUp {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(-20px); visibility: hidden; }
        }
        
        @media (max-width: 768px) {
            .detail-header h1 { font-size: 1.3em; }
            .detail-body { padding: 20px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="detail-container">
            <div class="detail-header">
                <div class="readonly-badge-header">🔍 نسخه عمومی - فقط مشاهده</div>
                <h1>$ <?php echo htmlspecialchars($command['command']); ?></h1>
                <span class="detail-category">📁 <?php echo htmlspecialchars($command['category']); ?></span>
                <div>
                    <button onclick="copyCommandWithSelect('<?php echo htmlspecialchars(addslashes($command['command'])); ?>')" class="detail-copy-btn">
                        📋📝 کپی دستور
                    </button>
                </div>
            </div>
            
            <div class="detail-body">
                <div class="info-section">
                    <h3>💻 دستور</h3>
                    <div class="command-select-box" onclick="selectAndCopy('<?php echo htmlspecialchars(addslashes($command['command'])); ?>', event)">
                        $ <?php echo htmlspecialchars($command['command']); ?>
                        <span style="font-size: 11px; color: #aaa; float: left;">👇 برای کپی کلیک کنید</span>
                    </div>
                </div>

                <div class="info-section">
                    <h3>📖 توضیحات کامل</h3>
                    <p><?php echo nl2br(htmlspecialchars($command['description'])); ?></p>
                </div>
                
                <?php if($command['example']): ?>
                <div class="info-section">
                    <h3>💡 مثال کاربردی</h3>
                    <pre><?php echo htmlspecialchars($command['example']); ?></pre>
                </div>
                <?php endif; ?>
                
                <?php if($command['keywords']): ?>
                <div class="info-section">
                    <h3>🔑 کلمات کلیدی</h3>
                    <div class="keyword-list">
                        <?php 
                        $keywords = explode(',', $command['keywords']);
                        foreach($keywords as $kw) {
                            echo "<span class='keyword-badge'>#" . htmlspecialchars(trim($kw)) . "</span>";
                        }
                        ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if(count($attached_files) > 0): ?>
                <div class="info-section">
                    <h3>📎 فایل‌های پیوست</h3>
                    <div class="similar-commands">
                        <?php foreach($attached_files as $file): ?>
                            <a href="<?php echo htmlspecialchars($file['file_path']); ?>" target="_blank" class="file-item">
                                📄 <?php echo htmlspecialchars($file['file_name']); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <div style="text-align: center; margin-top: 30px;">
                    <a href="index_readonly.php" class="back-btn">🔙 بازگشت به صفحه اصلی</a>
                </div>
            </div>
        </div>
    </div>

    <script>
    function copyCommandWithSelect(command) {
        const textarea = document.createElement('textarea');
        textarea.value = command;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showCopySuccess('✅ دستور "' + command + '" کپی شد!');
    }
    
    function selectAndCopy(command, event) {
        if(event) event.stopPropagation();
        const tempInput = document.createElement('input');
        tempInput.value = command;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        showCopySuccess('📋 دستور کپی شد!');
    }
    
    function showCopySuccess(message) {
        const existing = document.querySelector('.copy-success-toast');
        if(existing) existing.remove();
        const toast = document.createElement('div');
        toast.className = 'copy-success-toast';
        toast.innerHTML = '🎉 ' + message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2500);
    }
    </script>
</body>
</html>