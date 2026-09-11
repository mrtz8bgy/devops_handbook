<?php
require_once __DIR__ . '/../config.php';

// تنظیمات صفحه‌بندی
$limit = 15; // تعداد دستورات در هر صفحه
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// دریافت دسته‌بندی انتخاب شده
$selected_category = isset($_GET['cat']) ? $_GET['cat'] : 'all';

// ساخت کوئری بر اساس دسته‌بندی
if ($selected_category != 'all') {
    $count_sql = "SELECT COUNT(*) FROM commands WHERE category = ?";
    $count_stmt = $pdo->prepare($count_sql);
    $count_stmt->execute([$selected_category]);
    $total_records = $count_stmt->fetchColumn();
    
    $sql = "SELECT * FROM commands WHERE category = ? ORDER BY command ASC LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$selected_category]);
} else {
    $count_sql = "SELECT COUNT(*) FROM commands";
    $total_records = $pdo->query($count_sql)->fetchColumn();
    
    $sql = "SELECT * FROM commands ORDER BY command ASC LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

$commands = $stmt->fetchAll();
$total_pages = ceil($total_records / $limit);
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>کتاب راهنمای DevOps</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .table-view {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
        
        .commands-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .commands-table th {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 12px;
            text-align: right;
            font-weight: bold;
        }
        
        .commands-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }
        
        .commands-table tr:hover {
            background: #f8f9ff;
        }
        
        .command-cell {
            font-family: monospace;
            font-weight: bold;
            color: #764ba2;
            direction: ltr;
            text-align: left;
        }
        
        .category-badge-small {
            display: inline-block;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 11px;
        }
        
        .action-buttons-cell {
            white-space: nowrap;
        }
        
        .action-icon {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            padding: 5px 8px;
            border-radius: 8px;
            transition: all 0.2s;
            margin: 0 2px;
        }
        
        .action-icon:hover {
            transform: scale(1.1);
        }
        
        .copy-icon { background: #28a74520; color: #28a745; }
        .copy-icon:hover { background: #28a745; color: white; }
        
        .detail-icon { background: #667eea20; color: #667eea; }
        .detail-icon:hover { background: #667eea; color: white; }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 25px;
            flex-wrap: wrap;
        }
        
        .pagination a, .pagination span {
            padding: 8px 14px;
            background: white;
            border-radius: 8px;
            text-decoration: none;
            color: #667eea;
            transition: all 0.3s;
            border: 1px solid #e0e0e0;
        }
        
        .pagination a:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            transform: translateY(-2px);
        }
        
        .pagination .active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
        }
        
        .pagination .disabled {
            color: #ccc;
            cursor: not-allowed;
        }
        
        .page-info {
            text-align: center;
            margin-top: 15px;
            color: #666;
            font-size: 13px;
        }
        
        .view-switch {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 15px;
            gap: 10px;
        }
        
        .view-btn {
            background: white;
            border: 1px solid #ddd;
            padding: 6px 15px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 13px;
        }
        
        .view-btn.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
        }
        
        .cards-grid {
            display: none;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        
        .cards-grid.active-view {
            display: grid;
        }
        
        .table-view.active-view {
            display: block;
        }
        
        .copy-success-toast {
            position: fixed;
            bottom: 100px;
            right: 30px;
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 12px 20px;
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
            100% { opacity: 0; visibility: hidden; }
        }
        
        @media (max-width: 768px) {
            .commands-table th, .commands-table td {
                padding: 8px;
                font-size: 12px;
            }
            .command-cell {
                font-size: 11px;
            }
            .action-icon {
                font-size: 14px;
                padding: 3px 6px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 کتاب راهنمای DevOps</h1>
            <p>مرجع کامل دستورات لینوکس، داکر، گیت و...</p>
            <div class="nav-menu">
                <a href="index.php" class="nav-btn">🏠 صفحه اصلی</a>
                <a href="add.php" class="nav-btn">➕ افزودن دستور</a>
                <a href="search.php" class="nav-btn">🔍 جستجو</a>
                <a href="manage_categories.php" class="nav-btn">🏷️ مدیریت دسته‌بندی</a>
                <a href="chatbot.php" class="nav-btn">🤖 چت‌بات هوشمند</a>
                <a href="manage_qa.php" class="nav-btn">📋 مدیریت QA</a>
                <a href="manage_unknown.php" class="nav-btn">❓ سوالات بی‌جواب</a>
            </div>
        </div>

        <div class="search-box">
            <form action="search.php" method="GET" style="display: flex; width: 100%;">
                <input type="text" name="q" class="search-input" placeholder="جستجوی دستور، توضیحات یا کلمات کلیدی...">
                <button type="submit" class="search-btn">جستجو</button>
            </form>
        </div>

        <div class="categories" id="categories">
            <a href="?cat=all&page=1" class="cat-btn <?php echo $selected_category == 'all' ? 'active' : ''; ?>">همه</a>
            <?php
            $stmt = $pdo->query("SELECT category FROM categories ORDER BY category");
            while($row = $stmt->fetch()) {
                $active = ($selected_category == $row['category']) ? 'active' : '';
                echo "<a href='?cat=" . urlencode($row['category']) . "&page=1' class='cat-btn $active'>" . htmlspecialchars($row['category']) . "</a>";
            }
            ?>
        </div>

        <div class="view-switch">
            <button class="view-btn active" onclick="switchView('table')">📋 نمای جدولی</button>
            <button class="view-btn" onclick="switchView('card')">🃏 نمای کارتی</button>
        </div>

        <!-- نمای جدولی -->
        <div class="table-view active-view" id="tableView">
            <table class="commands-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>دستور</th>
                        <th>توضیحات</th>
                        <th>دسته‌بندی</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($commands) > 0): ?>
                        <?php foreach($commands as $index => $row): ?>
                            <tr>
                                <td style="width: 50px;"><?php echo $offset + $index + 1; ?></td>
                                <td class="command-cell"><?php echo htmlspecialchars($row['command']); ?></td>
                                <td><?php echo htmlspecialchars(mb_substr($row['description'], 0, 60)); ?>...</td>
                                <td><span class="category-badge-small"><?php echo htmlspecialchars($row['category']); ?></span></td>
                                <td class="action-buttons-cell">
                                    <button class="action-icon copy-icon" onclick="copyCommand('<?php echo htmlspecialchars(addslashes($row['command'])); ?>', event)" title="کپی">📋</button>
                                    <a href="command_detail.php?id=<?php echo $row['id']; ?>" class="action-icon detail-icon" title="جزئیات" style="display: inline-block; text-decoration: none;">🔍</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px;">
                                📭 هیچ دستوری در این دسته‌بندی یافت نشد!
                                <br><br>
                                <a href="add.php" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 8px 20px; border-radius: 20px; text-decoration: none;">➕ افزودن دستور جدید</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- نمای کارتی -->
        <div class="cards-grid" id="cardView">
            <?php if(count($commands) > 0): ?>
                <?php foreach($commands as $row): ?>
                    <div class="command-card" data-category="<?php echo htmlspecialchars($row['category']); ?>">
                        <div class="card-header">
                            <button class="copy-select-btn" onclick="copyCommandWithSelect('<?php echo htmlspecialchars(addslashes($row['command'])); ?>', event)">
                                📋📝 کپی و انتخاب
                            </button>
                            <h3><?php echo htmlspecialchars($row['command']); ?></h3>
                            <span class="category-badge"><?php echo htmlspecialchars($row['category']); ?></span>
                        </div>
                        <div class="card-body">
                            <div class="command-code" onclick="selectAndCopy('<?php echo htmlspecialchars(addslashes($row['command'])); ?>', event)">
                                $ <?php echo htmlspecialchars($row['command']); ?>
                                <span style="font-size: 11px; color: #999; float: left;">📋 کلیک کنید تا کپی شود</span>
                            </div>
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
                                <a href="command_detail.php?id=<?php echo $row['id']; ?>" style="color: #667eea; font-size: 13px; font-weight: bold; text-decoration: none;">
                                    🔍 مشاهده جزئیات کامل →
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="background: white; border-radius: 15px; padding: 40px; text-align: center; grid-column: 1/-1;">
                    <p style="font-size: 1.2em; color: #666;">📭 هیچ دستوری در این دسته‌بندی یافت نشد!</p>
                    <a href="add.php" style="display: inline-block; margin-top: 15px; padding: 10px 25px; background: linear-gradient(135deg, #667eea, #764ba2); color: white; text-decoration: none; border-radius: 25px;">➕ افزودن دستور جدید</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- صفحه‌بندی -->
        <?php if($total_pages > 1): ?>
            <div class="pagination">
                <?php if($page > 1): ?>
                    <a href="?cat=<?php echo urlencode($selected_category); ?>&page=<?php echo $page-1; ?>">&laquo; قبلی</a>
                <?php else: ?>
                    <span class="disabled">&laquo; قبلی</span>
                <?php endif; ?>
                
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <?php if($i == $page): ?>
                        <span class="active"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?cat=<?php echo urlencode($selected_category); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if($page < $total_pages): ?>
                    <a href="?cat=<?php echo urlencode($selected_category); ?>&page=<?php echo $page+1; ?>">بعدی &raquo;</a>
                <?php else: ?>
                    <span class="disabled">بعدی &raquo;</span>
                <?php endif; ?>
            </div>
            <div class="page-info">
                نمایش <?php echo $offset + 1; ?> تا <?php echo min($offset + $limit, $total_records); ?> از <?php echo $total_records; ?> دستور
            </div>
        <?php endif; ?>
    </div>

    <button onclick="scrollToTop()" class="scroll-top-btn" id="scrollTopBtn" title="بازگشت به بالا">↑</button>

    <script>
    // کپی برای نمای جدولی
    function copyCommand(command, event) {
        event.stopPropagation();
        const tempInput = document.createElement('input');
        tempInput.value = command;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        showCopySuccess('✅ دستور "' + command + '" کپی شد!');
    }
    
    function copyCommandWithSelect(command, event) {
        event.stopPropagation();
        const textarea = document.createElement('textarea');
        textarea.value = command;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showCopySuccess('✅ دستور "' + command + '" کپی شد! 🎉');
    }
    
    function selectAndCopy(command, event) {
        event.stopPropagation();
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
    
    function switchView(view) {
        const tableView = document.getElementById('tableView');
        const cardView = document.getElementById('cardView');
        const btns = document.querySelectorAll('.view-btn');
        btns.forEach(btn => btn.classList.remove('active'));
        if(view === 'table') {
            tableView.classList.add('active-view');
            cardView.classList.remove('active-view');
            btns[0].classList.add('active');
            localStorage.setItem('preferredView', 'table');
        } else {
            cardView.classList.add('active-view');
            tableView.classList.remove('active-view');
            btns[1].classList.add('active');
            localStorage.setItem('preferredView', 'card');
        }
    }
    
    const savedView = localStorage.getItem('preferredView');
    if(savedView === 'card') switchView('card');
    else switchView('table');
    
    function scrollToTop() {
        window.scrollTo({top: 0, behavior: 'smooth'});
    }
    
    window.addEventListener('scroll', function() {
        const scrollBtn = document.getElementById('scrollTopBtn');
        if (window.pageYOffset > 300) scrollBtn.classList.add('show');
        else scrollBtn.classList.remove('show');
    });
    </script>
</body>
</html>