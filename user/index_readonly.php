<?php
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>کتاب راهنمای DevOps - نسخه عمومی</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .readonly-badge {
            background: #28a745;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-block;
            margin-right: 10px;
        }
        
        .warning-box {
            background: #fff3cd;
            border-right: 4px solid #ffc107;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .action-buttons {
            display: none !important;
        }
        
        .edit-btn, .delete-btn {
            display: none !important;
        }
        
        .nav-menu .nav-btn:not(.home-only) {
            display: none;
        }
        
        .add-button {
            display: none;
        }
        
        .command-card .card-header {
            position: relative;
        }
        
        .readonly-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.5);
            color: white;
            padding: 3px 8px;
            border-radius: 5px;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 کتاب راهنمای DevOps <span class="readonly-badge">نسخه عمومی - فقط خواندنی</span></h1>
            <p>مرجع کامل دستورات لینوکس، داکر، گیت و... - دسترسی فقط برای مشاهده و جستجو</p>
            <div class="warning-box">
                ⚠️ این نسخه فقط برای مشاهده و جستجو است. در صورت نیاز به افزودن دستور جدید، با مدیر سیستم تماس بگیرید.
            </div>
            <div class="nav-menu">
                <a href="index_readonly.php" class="nav-btn home-only">🏠 صفحه اصلی</a>
                <a href="search_readonly.php" class="nav-btn home-only">🔍 جستجوی پیشرفته</a>
                <a href="chatbot.php" class="nav-btn home-only">🔍 چت بات</a>
            </div>
        </div>

        <div class="search-box">
            <form action="search_readonly.php" method="GET" style="display: flex; width: 100%;">
                <input type="text" name="q" class="search-input" placeholder="جستجوی دستور، توضیحات یا کلمات کلیدی...">
                <button type="submit" class="search-btn">جستجو</button>
            </form>
        </div>

        <div class="categories" id="categories">
            <button class="cat-btn active" onclick="filterByCategory('all')">همه</button>
            <?php
            $stmt = $pdo->query("SELECT category FROM categories ORDER BY category");
            while($row = $stmt->fetch()) {
                echo "<button class='cat-btn' onclick='filterByCategory(\"" . htmlspecialchars($row['category']) . "\")'>" . htmlspecialchars($row['category']) . "</button>";
            }
            ?>
        </div>

        <div class="cards-grid" id="commandsContainer">
            <?php
            $stmt = $pdo->query("SELECT * FROM commands ORDER BY created_at DESC");
            if($stmt->rowCount() > 0) {
                while($row = $stmt->fetch()) {
                    ?>
                    <div class="command-card" data-category="<?php echo htmlspecialchars($row['category']); ?>">
                        <a href="command_detail_readonly.php?id=<?php echo $row['id']; ?>" style="text-decoration: none; color: inherit;">
                            <div class="card-header">
                                <div class="readonly-icon">🔍 فقط مشاهده</div>
                                <h3><?php echo htmlspecialchars($row['command']); ?></h3>
                                <span class="category-badge"><?php echo htmlspecialchars($row['category']); ?></span>
                            </div>
                            <div class="card-body">
                                <div class="command-code">$ <?php echo htmlspecialchars($row['command']); ?></div>
                                <div class="description">
                                    <?php 
                                    $short_desc = mb_substr(strip_tags($row['description']), 0, 120);
                                    echo htmlspecialchars($short_desc) . '...';
                                    ?>
                                </div>
                                <?php if($row['example']): ?>
                                    <div class="command-code" style="background: #e8f0fe; margin-top: 10px;">
                                        📝 مثال: <?php echo htmlspecialchars(mb_substr($row['example'], 0, 50)); ?>
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
                                    <span style="color: #667eea; font-size: 13px; font-weight: bold;">🔍 مشاهده جزئیات کامل →</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div style="background: white; border-radius: 15px; padding: 40px; text-align: center; grid-column: 1/-1;">
                    <p style="font-size: 1.2em; color: #666;">📭 در حال حاضر دستوری در سیستم وجود ندارد.</p>
                </div>
                <?php
            }
            ?>
        </div>
    </div>

    <button onclick="scrollToTop()" class="scroll-top-btn" id="scrollTopBtn" title="بازگشت به بالا">↑</button>

    <script>
    function filterByCategory(category) {
        const cards = document.querySelectorAll('.command-card');
        const btns = document.querySelectorAll('.cat-btn');
        
        btns.forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');
        
        cards.forEach(card => {
            if(category === 'all' || card.dataset.category === category) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function scrollToTop() {
        window.scrollTo({top: 0, behavior: 'smooth'});
    }
    
    window.addEventListener('scroll', function() {
        const scrollBtn = document.getElementById('scrollTopBtn');
        if (window.pageYOffset > 300) {
            scrollBtn.classList.add('show');
        } else {
            scrollBtn.classList.remove('show');
        }
    });
    </script>
</body>
</html>