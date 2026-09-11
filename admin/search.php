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
    <title>جستجوی دستورات</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .search-result-card {
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
        }
        .search-result-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
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
            transition: all 0.3s;
            text-align: center;
        }
        .detail-link:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
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
            gap: 10px;
        }
        .result-count {
            background: #667eea;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
        }
        
        /* دکمه کپی در کارت جستجو */
        .search-copy-btn {
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
            transition: all 0.3s;
        }
        
        .search-copy-btn:hover {
            background: #667eea;
            transform: scale(1.05);
        }
        
        /* نوتیفیکیشن */
        .toast-notification {
            position: fixed;
            bottom: 100px;
            right: 30px;
            background: #28a745;
            color: white;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 14px;
            z-index: 1000;
            animation: slideIn 0.3s ease, fadeOut 2s ease 1.7s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            direction: ltr;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes fadeOut {
            0% { opacity: 1; }
            70% { opacity: 1; }
            100% { opacity: 0; }
        }
        
        .card-header {
            position: relative;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔍 جستجوی پیشرفته</h1>
            <p>جستجو بر اساس نام دستور، توضیحات یا کلمات کلیدی</p>
            <div class="nav-menu">
                <a href="index.php" class="nav-btn">🏠 صفحه اصلی</a>
                <a href="add.php" class="nav-btn">➕ افزودن دستور</a>
                <a href="manage_categories.php" class="nav-btn">🏷️ مدیریت دسته‌بندی</a>
            </div>
        </div>

        <div class="search-box">
            <form method="GET" style="display: flex; width: 100%;">
                <input type="text" name="q" class="search-input" placeholder="جستجو بر اساس نام دستور، توضیحات یا کلمات کلیدی..." value="<?php echo htmlspecialchars($searchTerm); ?>">
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
                    <div class="command-card search-result-card">
                        <div class="card-header">
                            <button onclick="copyToClipboard('<?php echo htmlspecialchars(addslashes($row['command'])); ?>', event)" 
                                    class="search-copy-btn">
                                📋 کپی
                            </button>
                            <h3><?php echo htmlspecialchars($row['command']); ?></h3>
                            <span class="category-badge">📁 <?php echo htmlspecialchars($row['category']); ?></span>
                        </div>
                        <div class="card-body">
                            <div class="command-code">$ <?php echo htmlspecialchars($row['command']); ?></div>
                            <div class="description">
                                <?php 
                                // نمایش خلاصه از توضیحات (150 کاراکتر اول)
                                $short_desc = mb_substr(strip_tags($row['description']), 0, 150);
                                echo htmlspecialchars($short_desc);
                                if(mb_strlen($row['description']) > 150) echo '...';
                                ?>
                            </div>
                            <?php if($row['example']): ?>
                                <div class="command-code" style="background: #e8f0fe; margin-top: 10px;">
                                    📝 مثال: <?php echo htmlspecialchars(mb_substr($row['example'], 0, 60)); ?>
                                    <?php if(mb_strlen($row['example']) > 60) echo '...'; ?>
                                </div>
                            <?php endif; ?>
                            <?php if($row['keywords']): ?>
                                <div class="keywords">
                                    <?php 
                                    $keywords = explode(',', $row['keywords']);
                                    $counter = 0;
                                    foreach($keywords as $kw) {
                                        if($counter < 3) {
                                            echo "<span class='keyword-tag'>#" . htmlspecialchars(trim($kw)) . "</span>";
                                            $counter++;
                                        }
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>
                            <div style="margin-top: 15px; text-align: left; direction: ltr;">
                                <a href="command_detail.php?id=<?php echo $row['id']; ?>" class="detail-link">🔍 مشاهده جزئیات کامل →</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <?php if(count($results) == 0): ?>
                    <div style="background: white; padding: 60px 40px; text-align: center; border-radius: 15px; grid-column: 1/-1;">
                        <div style="font-size: 48px; margin-bottom: 20px;">🔍❌</div>
                        <h3 style="color: #666; margin-bottom: 15px;">هیچ دستوری با این کلمه کلیدی پیدا نشد!</h3>
                        <p style="color: #999; margin-bottom: 25px;">کلمات دیگری را جستجو کنید یا دستور جدیدی به کتابخانه اضافه کنید.</p>
                        <a href="add.php" class="detail-link" style="background: #28a745;">➕ افزودن دستور جدید</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div style="background: white; padding: 60px 40px; text-align: center; border-radius: 15px; margin-top: 30px;">
                <div style="font-size: 48px; margin-bottom: 20px;">🔍</div>
                <h3 style="color: #666; margin-bottom: 15px;">چیزی که دنبالشی رو تایپ کن...</h3>
                <p style="color: #999;">مثال: docker, git, ls, psql, nginx</p>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // قابلیت جستجو با کلید Enter
        const searchInput = document.querySelector('.search-input');
        if(searchInput) {
            searchInput.focus();
        }
        
        // تابع کپی به کلیپ‌بورد
        function copyToClipboard(command, event) {
            // جلوگیری از باز شدن لینک
            if(event) {
                event.stopPropagation();
            }
            
            // کپی کردن دستور
            navigator.clipboard.writeText(command).then(function() {
                showNotification('✅ دستور "' + command + '" کپی شد!');
            }).catch(function(err) {
                console.error('خطا در کپی: ', err);
                showNotification('❌ خطا در کپی دستور', 'error');
            });
        }
        
        // تابع نمایش نوتیفیکیشن
        function showNotification(message, type = 'success') {
            // حذف نوتیفیکیشن قبلی اگر وجود داشته باشد
            const existingToast = document.querySelector('.toast-notification');
            if(existingToast) {
                existingToast.remove();
            }
            
            // ساخت نوتیفیکیشن جدید
            const toast = document.createElement('div');
            toast.className = 'toast-notification';
            toast.style.background = type === 'success' ? '#28a745' : '#dc3545';
            toast.innerHTML = message;
            document.body.appendChild(toast);
            
            // حذف نوتیفیکیشن بعد از 2.5 ثانیه
            setTimeout(function() {
                if(toast && toast.remove) {
                    toast.remove();
                }
            }, 2500);
        }
    </script>
</body>
</html>