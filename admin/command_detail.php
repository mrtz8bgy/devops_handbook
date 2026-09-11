<?php
require_once 'config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$command = null;

if($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM commands WHERE id = ?");
    $stmt->execute([$id]);
    $command = $stmt->fetch();
    
    if(!$command) {
        header("Location: index.php");
        exit;
    }
}

// دریافت فایل‌های پیوست شده برای این دستور
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
        
        .detail-copy-btn:hover {
            background: rgba(255,255,255,0.4);
            transform: scale(1.05);
        }
        
        .command-select-box {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 10px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.6;
            overflow-x: auto;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }
        
        .command-select-box:hover {
            background: #1e1e1e;
            transform: translateY(-2px);
        }
        
        .command-select-box:active {
            transform: scale(0.99);
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
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .info-section pre {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 20px;
            border-radius: 10px;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.6;
            position: relative;
        }
        
        /* دکمه کپی برای pre */
        .copy-pre-btn {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 5px 12px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 11px;
            transition: all 0.3s;
        }
        
        .copy-pre-btn:hover {
            background: rgba(255,255,255,0.4);
        }
        
        .info-section pre {
            position: relative;
        }
        
        .info-section p {
            font-size: 1.1em;
            line-height: 1.8;
            color: #555;
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
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .similar-command:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }
        
        .attached-files {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 15px;
        }
        
        .file-item {
            background: #f8f9fa;
            padding: 12px 20px;
            border-radius: 12px;
            text-decoration: none;
            color: #667eea;
            font-weight: bold;
            transition: all 0.3s;
            border: 1px solid #e0e0e0;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .file-item:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102,126,234,0.3);
        }
        
        .file-icon {
            font-size: 20px;
        }
        
        .file-size {
            font-size: 11px;
            opacity: 0.7;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .action-btn {
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .edit-btn {
            background: #ffc107;
            color: #333;
        }
        
        .delete-btn {
            background: #dc3545;
            color: white;
        }
        
        .back-btn {
            background: #6c757d;
            color: white;
        }
        
        .chatbot-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        
        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .example-output {
            background: #f8f9fa;
            border-right: 4px solid #28a745;
            padding: 15px;
            margin-top: 15px;
            font-family: monospace;
            position: relative;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .example-output:hover {
            background: #e8f0fe;
            transform: translateY(-2px);
        }
        
        .copy-example-btn {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #28a745;
            border: none;
            color: white;
            padding: 4px 10px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 11px;
            transition: all 0.3s;
        }
        
        .copy-example-btn:hover {
            background: #1e7e34;
            transform: scale(1.05);
        }

        .copy-success-toast {
            position: fixed;
            bottom: 100px;
            right: 30px;
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 15px 25px;
            border-radius: 12px;
            font-size: 14px;
            z-index: 1000;
            animation: slideInRight 0.3s ease, fadeOutUp 0.5s ease 2s forwards;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            direction: ltr;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: bold;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes fadeOutUp {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-20px);
                visibility: hidden;
            }
        }

        @media (max-width: 768px) {
            .detail-header h1 {
                font-size: 1.3em;
            }
            .detail-body {
                padding: 20px;
            }
            .info-section h3 {
                font-size: 1.2em;
            }
            .action-btn {
                padding: 8px 20px;
                font-size: 14px;
            }
            .detail-copy-btn {
                padding: 6px 12px;
                font-size: 11px;
            }
            .file-item {
                padding: 8px 15px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="detail-container">
            <div class="detail-header">
                <h1>$ <?php echo htmlspecialchars($command['command']); ?></h1>
                <span class="detail-category">📁 <?php echo htmlspecialchars($command['category']); ?></span>
                <div>
                    <button onclick="copyCommandWithSelect('<?php echo htmlspecialchars(addslashes($command['command'])); ?>')" class="detail-copy-btn">
                        📋📝 کپی دستور (انتخاب خودکار)
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
                    <div style="position: relative;">
                        <button class="copy-pre-btn" onclick="copyExample('<?php echo htmlspecialchars(addslashes($command['example'])); ?>')">📋 کپی مثال</button>
                        <pre><?php echo htmlspecialchars($command['example']); ?></pre>
                    </div>
                    <div class="example-output" onclick="copyExample('<?php echo htmlspecialchars(addslashes($command['example'])); ?>')">
                        <button class="copy-example-btn" onclick="event.stopPropagation(); copyExample('<?php echo htmlspecialchars(addslashes($command['example'])); ?>')">📋 کپی</button>
                        <strong>🔧 نحوه اجرا:</strong><br>
                        <span style="color: #28a745; font-family: monospace;">$ <?php echo htmlspecialchars($command['example']); ?></span>
                    </div>
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
                
                <?php if($command['similar_commands']): ?>
                <div class="info-section">
                    <h3>🔄 دستورات مشابه</h3>
                    <div class="similar-commands">
                        <?php 
                        $similars = explode(',', $command['similar_commands']);
                        foreach($similars as $sim) {
                            $sim = trim($sim);
                            $stmt = $pdo->prepare("SELECT id FROM commands WHERE command LIKE ?");
                            $stmt->execute(["%$sim%"]);
                            if($stmt->rowCount() > 0) {
                                $sim_id = $stmt->fetch()['id'];
                                echo "<a href='command_detail.php?id=$sim_id' class='similar-command'>📌 " . htmlspecialchars($sim) . "</a>";
                            } else {
                                echo "<span class='similar-command' style='background:#e9ecef;'>📌 " . htmlspecialchars($sim) . "</span>";
                            }
                        }
                        ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- بخش فایل‌های پیوست -->
                <?php if(count($attached_files) > 0): ?>
                <div class="info-section">
                    <h3>📎 فایل‌های پیوست شده</h3>
                    <div class="attached-files">
                        <?php foreach($attached_files as $file): 
                            $file_icon = '📄';
                            $file_ext = strtolower(pathinfo($file['file_name'], PATHINFO_EXTENSION));
                            
                            if($file_ext == 'pdf') $file_icon = '📕';
                            elseif($file_ext == 'jpg' || $file_ext == 'jpeg' || $file_ext == 'png') $file_icon = '🖼️';
                            elseif($file_ext == 'yml' || $file_ext == 'yaml') $file_icon = '⚙️';
                            elseif($file_ext == 'json') $file_icon = '📋';
                            elseif($file_ext == 'txt') $file_icon = '📝';
                            elseif($file_ext == 'md') $file_icon = '📘';
                            ?>
                            <a href="<?php echo htmlspecialchars($file['file_path']); ?>" target="_blank" class="file-item">
                                <span class="file-icon"><?php echo $file_icon; ?></span>
                                <span><?php echo htmlspecialchars($file['file_name']); ?></span>
                                <span class="file-size">(<?php echo round($file['file_size'] / 1024, 2); ?> KB)</span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="action-buttons">
                    <a href="edit_command.php?id=<?php echo $command['id']; ?>" class="action-btn edit-btn">✏️ ویرایش دستور</a>
                    <a href="delete_command.php?id=<?php echo $command['id']; ?>" 
                       onclick="return confirm('آیا از حذف دستور <?php echo $command['command']; ?> مطمئن هستید؟')" 
                       class="action-btn delete-btn">🗑️ حذف دستور</a>
                    <a href="javascript:history.back()" class="action-btn back-btn">🔙 بازگشت</a>
                    <a href="chatbot.php" class="action-btn chatbot-btn">🤖 چت بات</a>
                    <a href="index.php" class="action-btn back-btn">🏠 صفحه اصلی</a>
                </div>
            </div>
        </div>
    </div>

    <script>
    // کپی دستور اصلی
    function copyCommandWithSelect(command) {
        const textarea = document.createElement('textarea');
        textarea.value = command;
        document.body.appendChild(textarea);
        textarea.select();
        textarea.setSelectionRange(0, 99999);
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showCopySuccess('✅ دستور "' + command + '" کپی شد! 🎉');
    }
    
    // کپی با کلیک روی باکس دستور
    function selectAndCopy(command, event) {
        if(event) event.stopPropagation();
        const tempInput = document.createElement('input');
        tempInput.value = command;
        document.body.appendChild(tempInput);
        tempInput.select();
        tempInput.setSelectionRange(0, 99999);
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        showCopySuccess('📋 دستور کپی شد!');
        
        if(event.target) {
            const originalBg = event.target.style.background;
            event.target.style.background = '#28a745';
            event.target.style.color = 'white';
            setTimeout(() => {
                event.target.style.background = originalBg;
            }, 200);
        }
    }
    
    // کپی مثال
    function copyExample(example) {
        const tempInput = document.createElement('input');
        tempInput.value = example;
        document.body.appendChild(tempInput);
        tempInput.select();
        tempInput.setSelectionRange(0, 99999);
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        showCopySuccess('📋 مثال کپی شد!');
    }
    
    // نمایش نوتیفیکیشن
    function showCopySuccess(message) {
        const existing = document.querySelector('.copy-success-toast');
        if(existing) existing.remove();
        
        const toast = document.createElement('div');
        toast.className = 'copy-success-toast';
        toast.innerHTML = '🎉 ' + message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            if(toast && toast.remove) toast.remove();
        }, 2500);
    }
    </script>
</body>
</html>