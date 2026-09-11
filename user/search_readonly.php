<?php
require_once 'config.php';

$results = [];
$searchTerm = '';

if(isset($_GET['q']) && !empty($_GET['q'])) {
    $searchTerm = $_GET['q'];
    $sql = "SELECT * FROM commands WHERE 
            command LIKE :search OR 
            description LIKE :search OR 
            keywords LIKE :search";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['search' => "%$searchTerm%"]);
    $results = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>جستجوی دستورات - نسخه عمومی</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .readonly-badge {
            background: #28a745;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
        }
        
        .result-header {
            background: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .result-count {
            background: #667eea;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
        }
        
        .detail-link {
            display: inline-block;
            margin-top: 15px;
            padding: 8px 20px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            text-decoration: none;
            border-radius: 20px;
            font-size: 14px;
        }
        
        .copy-search-btn {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(0,0,0,0.6);
            border: none;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            z-index: 20;
        }
        
        .copy-search-btn:hover {
            background: #28a745;
        }
        
        .toast-notification {
            position: fixed;
            bottom: 100px;
            right: 30px;
            background: #28a745;
            color: white;
            padding: 12px 24px;
            border-radius: 10px;
            z-index: 1000;
            animation: slideIn 0.3s ease, fadeOut 2s ease 1.7s;
        }
        
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes fadeOut {
            0% { opacity: 1; }
            70% { opacity: 1; }
            100% { opacity: 0; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔍 جستجوی پیشرفته <span class="readonly-badge">نسخه عمومی</span></h1>
            <p>جستجو بر اساس نام دستور، توضیحات یا کلمات کلیدی</p>
            <div class="nav-menu">
                <a href="index_readonly.php" class="nav-btn">🏠 صفحه اصلی</a>
            </div>
        </div>

        <div class="search-box">
            <form method="GET" style="display: flex; width: 100%;">
                <input type="text" name="q" class="search-input" placeholder="جستجو..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit" class="search-btn">جستجو</button>
            </form>
        </div>

        <?php if($searchTerm): ?>
            <div class="result-header">
                <strong>🔎 نتیجه جستجو برای: "<?php echo htmlspecialchars($searchTerm); ?>"</strong>
                <span class="result-count">📊 تعداد نتایج: <?php echo count($results); ?></span>
            </div>

            <div class="cards-grid">
                <?php foreach($results as $row): ?>
                    <div class="command-card" style="position: relative;">
                        <button onclick="copyToClipboard('<?php echo htmlspecialchars(addslashes($row['command'])); ?>', event)" class="copy-search-btn">
                            📋 کپی
                        </button>
                        <a href="command_detail_readonly.php?id=<?php echo $row['id']; ?>" style="text-decoration: none; color: inherit;">
                            <div class="card-header">
                                <h3><?php echo htmlspecialchars($row['command']); ?></h3>
                                <span class="category-badge">📁 <?php echo htmlspecialchars($row['category']); ?></span>
                            </div>
                            <div class="card-body">
                                <div class="command-code">$ <?php echo htmlspecialchars($row['command']); ?></div>
                                <div class="description">
                                    <?php 
                                    $short_desc = mb_substr(strip_tags($row['description']), 0, 150);
                                    echo htmlspecialchars($short_desc) . '...';
                                    ?>
                                </div>
                                <div style="margin-top: 15px; text-align: left;">
                                    <span class="detail-link">🔍 مشاهده جزئیات کامل →</span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
                
                <?php if(count($results) == 0): ?>
                    <div style="background: white; padding: 60px; text-align: center; border-radius: 15px;">
                        <h3>❌ هیچ دستوری پیدا نشد!</h3>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div style="background: white; padding: 60px; text-align: center; border-radius: 15px; margin-top: 30px;">
                <h3>🔍 چیزی که دنبالشی رو تایپ کن...</h3>
                <p>مثال: docker, git, ls, psql, nginx</p>
            </div>
        <?php endif; ?>
    </div>

    <script>
    function copyToClipboard(command, event) {
        if(event) event.stopPropagation();
        navigator.clipboard.writeText(command).then(function() {
            showNotification('✅ دستور "' + command + '" کپی شد!');
        });
    }
    
    function showNotification(message) {
        const existing = document.querySelector('.toast-notification');
        if(existing) existing.remove();
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.innerHTML = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2500);
    }
    </script>
</body>
</html>