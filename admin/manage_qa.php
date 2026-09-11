<?php
require_once __DIR__ . '/../config.php';
require_admin($pdo); // v3: فقط ادمین

// متغیرها
$success = '';
$error = '';
$edit_id = null;
$edit_data = null;

// دریافت اطلاعات برای ویرایش
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM chatbot_qa WHERE id = ?");
    $stmt->execute([$edit_id]);
    $edit_data = $stmt->fetch(PDO::FETCH_ASSOC);
}

// اضافه کردن سوال و جواب جدید
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_qa'])) {
    $question = trim($_POST['question']);
    $answer = trim($_POST['answer']);
    $keywords = trim($_POST['keywords']);
    $category = trim($_POST['category']);
    
    if (empty($question) || empty($answer)) {
        $error = "❌ سوال و جواب نمی‌تواند خالی باشد!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO chatbot_qa (question, answer, keywords, category) VALUES (?, ?, ?, ?)");
        $stmt->execute([$question, $answer, $keywords, $category]);
        $success = "✅ سوال و جواب جدید اضافه شد!";
    }
}

// ویرایش سوال و جواب
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_qa'])) {
    $id = (int)$_POST['id'];
    $question = trim($_POST['question']);
    $answer = trim($_POST['answer']);
    $keywords = trim($_POST['keywords']);
    $category = trim($_POST['category']);
    
    if (empty($question) || empty($answer)) {
        $error = "❌ سوال و جواب نمی‌تواند خالی باشد!";
    } else {
        $stmt = $pdo->prepare("UPDATE chatbot_qa SET question = ?, answer = ?, keywords = ?, category = ? WHERE id = ?");
        $stmt->execute([$question, $answer, $keywords, $category, $id]);
        $success = "✅ سوال و جواب با موفقیت ویرایش شد!";
        $edit_id = null;
        $edit_data = null;
    }
}

// حذف سوال و جواب
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM chatbot_qa WHERE id = ?");
    $stmt->execute([$id]);
    $success = "✅ حذف شد!";
}

// جستجو
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($search) {
    $stmt = $pdo->prepare("SELECT * FROM chatbot_qa WHERE question LIKE ? OR answer LIKE ? OR keywords LIKE ? ORDER BY usage_count DESC, id DESC");
    $searchTerm = "%$search%";
    $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
} else {
    $stmt = $pdo->query("SELECT * FROM chatbot_qa ORDER BY usage_count DESC, id DESC");
}
$qa_list = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>مدیریت سوال و جواب چت‌بات</title>
    <link rel="stylesheet" href="../assets/css/luxury.css?v=3.1">
    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 25px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        .header h1 {
            color: #667eea;
            margin-bottom: 15px;
        }
        .nav-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .nav-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            transition: transform 0.3s;
            display: inline-block;
        }
        .nav-btn:hover {
            transform: translateY(-2px);
        }
        .form-container {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 20px;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
        }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }
        .submit-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: transform 0.3s;
        }
        .submit-btn:hover {
            transform: scale(1.02);
        }
        .edit-btn {
            background: #ffc107;
            color: #333;
            padding: 5px 12px;
            border-radius: 15px;
            text-decoration: none;
            font-size: 12px;
            margin-left: 8px;
            display: inline-block;
        }
        .delete-btn {
            background: #dc3545;
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            text-decoration: none;
            font-size: 12px;
            display: inline-block;
        }
        .edit-btn:hover, .delete-btn:hover {
            transform: scale(1.05);
        }
        .search-box {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }
        .search-box input {
            flex: 1;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            font-size: 14px;
        }
        .search-box button {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            cursor: pointer;
        }
        .clear-search {
            background: #6c757d;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        th, td {
            padding: 12px;
            text-align: right;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
        }
        th {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        .qa-question {
            font-weight: bold;
            color: #667eea;
            width: 20%;
        }
        .qa-answer {
            width: 45%;
        }
        .qa-actions {
            width: 15%;
            white-space: nowrap;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            margin-top: 5px;
        }
        .badge-general { background: #28a745; color: white; }
        .badge-guide { background: #17a2b8; color: white; }
        .badge-info { background: #ffc107; color: #333; }
        .badge-education { background: #6f42c1; color: white; }
        .badge-devops { background: #fd7e14; color: white; }
        .badge-coding { background: #20c997; color: white; }
        .badge-animals { background: #e83e8c; color: white; }
        .badge-objects { background: #6c757d; color: white; }
        .badge-fruits { background: #dc3545; color: white; }
        .badge-nature { background: #28a745; color: white; }
        .badge-colors { background: #6610f2; color: white; }
        .badge-motivation { background: #fd7e14; color: white; }
        .badge-fun { background: #20c997; color: white; }
        
        .pagination {
            margin-top: 20px;
            text-align: center;
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
        @media (max-width: 768px) {
            .container { padding: 15px; }
            th, td { font-size: 12px; padding: 8px; }
            .qa-actions { white-space: normal; }
            table { display: block; overflow-x: auto; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>📚 مدیریت سوال و جواب چت‌بات</h1>
        <p>✨ افزودن، ویرایش، حذف و جستجوی جملات هوشمند چت‌بات</p>
        <div class="nav-buttons">
            <a href="../index.php" class="nav-btn">🏠 صفحه اصلی</a>
            <a href="chatbot.php" class="nav-btn">🤖 چت‌بات</a>
            <a href="manage_qa.php" class="nav-btn">🔄 تازه سازی</a>
        </div>
    </div>
    
    <?php if(isset($success) && $success): ?>
        <div class="alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if(isset($error) && $error): ?>
        <div class="alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <!-- فرم افزودن/ویرایش -->
    <div class="form-container">
        <h3><?php echo $edit_data ? '✏️ ویرایش سوال و جواب' : '➕ افزودن سوال و جواب جدید'; ?></h3>
        <form method="POST">
            <?php if($edit_data): ?>
                <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label>📝 سوال:</label>
                <input type="text" name="question" required 
                       value="<?php echo $edit_data ? htmlspecialchars($edit_data['question']) : ''; ?>"
                       placeholder="مثال: سلام, چطوری, دستورات داکر">
            </div>
            
            <div class="form-group">
                <label>💬 جواب:</label>
                <textarea name="answer" required rows="3" 
                          placeholder="مثال: 👋 سلام! چطور میتونم کمکت کنم؟"><?php echo $edit_data ? htmlspecialchars($edit_data['answer']) : ''; ?></textarea>
            </div>
            
            <div class="form-group">
                <label>🔑 کلمات کلیدی (با کاما جدا کن):</label>
                <input type="text" name="keywords" 
                       value="<?php echo $edit_data ? htmlspecialchars($edit_data['keywords']) : ''; ?>"
                       placeholder="مثال: سلام, سلامتی, علیک">
                <small style="color: #999;">این کلمات به تشخیص بهتر سوال کمک میکنند</small>
            </div>
            
            <div class="form-group">
                <label>📂 دسته‌بندی:</label>
                <select name="category">
                    <option value="general" <?php echo ($edit_data && $edit_data['category'] == 'general') ? 'selected' : ''; ?>>general (عمومی)</option>
                    <option value="guide" <?php echo ($edit_data && $edit_data['category'] == 'guide') ? 'selected' : ''; ?>>guide (راهنما)</option>
                    <option value="info" <?php echo ($edit_data && $edit_data['category'] == 'info') ? 'selected' : ''; ?>>info (اطلاعاتی)</option>
                    <option value="education" <?php echo ($edit_data && $edit_data['category'] == 'education') ? 'selected' : ''; ?>>education (آموزشی)</option>
                    <option value="devops" <?php echo ($edit_data && $edit_data['category'] == 'devops') ? 'selected' : ''; ?>>devops (دواپس)</option>
                    <option value="coding" <?php echo ($edit_data && $edit_data['category'] == 'coding') ? 'selected' : ''; ?>>coding (برنامه‌نویسی)</option>
                    <option value="animals" <?php echo ($edit_data && $edit_data['category'] == 'animals') ? 'selected' : ''; ?>>animals (حیوانات)</option>
                    <option value="objects" <?php echo ($edit_data && $edit_data['category'] == 'objects') ? 'selected' : ''; ?>>objects (اشیاء)</option>
                    <option value="fruits" <?php echo ($edit_data && $edit_data['category'] == 'fruits') ? 'selected' : ''; ?>>fruits (میوه‌ها)</option>
                    <option value="nature" <?php echo ($edit_data && $edit_data['category'] == 'nature') ? 'selected' : ''; ?>>nature (طبیعت)</option>
                    <option value="colors" <?php echo ($edit_data && $edit_data['category'] == 'colors') ? 'selected' : ''; ?>>colors (رنگ‌ها)</option>
                    <option value="motivation" <?php echo ($edit_data && $edit_data['category'] == 'motivation') ? 'selected' : ''; ?>>motivation (انگیزشی)</option>
                    <option value="fun" <?php echo ($edit_data && $edit_data['category'] == 'fun') ? 'selected' : ''; ?>>fun (سرگرمی)</option>
                </select>
            </div>
            
            <button type="submit" name="<?php echo $edit_data ? 'edit_qa' : 'add_qa'; ?>" class="submit-btn">
                <?php echo $edit_data ? '💾 ذخیره تغییرات' : '➕ افزودن سوال و جواب'; ?>
            </button>
            
            <?php if($edit_data): ?>
                <a href="manage_qa.php" class="submit-btn" style="background: #6c757d; text-decoration: none; margin-right: 10px;">❌ انصراف</a>
            <?php endif; ?>
        </form>
    </div>
    
    <!-- جستجو -->
    <div class="search-box">
        <form method="GET" style="display: flex; gap: 10px; width: 100%;">
            <input type="text" name="search" placeholder="جستجو در سوالات، جواب‌ها و کلمات کلیدی..." 
                   value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">🔍 جستجو</button>
            <?php if($search): ?>
                <a href="manage_qa.php" class="clear-search" style="background: #6c757d; color: white; padding: 12px 25px; border-radius: 25px; text-decoration: none;">🗑️ پاک کردن</a>
            <?php endif; ?>
        </form>
    </div>
    
    <!-- لیست سوال و جواب‌ها -->
    <h3>📋 لیست سوال و جواب‌ها <?php echo $search ? "(نتایج جستجو: " . count($qa_list) . ")" : ""; ?></h3>
    
    <?php if(count($qa_list) == 0): ?>
        <div style="background: #f8f9fa; padding: 40px; text-align: center; border-radius: 15px;">
            <p>📭 هیچ سوال و جوابی یافت نشد!</p>
            <a href="manage_qa.php" class="nav-btn">🔄 نمایش همه</a>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>سوال</th>
                        <th>جواب</th>
                        <th>دسته‌بندی</th>
                        <th>استفاده</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($qa_list as $qa): 
                        $badge_class = '';
                        switch($qa['category']) {
                            case 'general': $badge_class = 'badge-general'; break;
                            case 'guide': $badge_class = 'badge-guide'; break;
                            case 'info': $badge_class = 'badge-info'; break;
                            case 'education': $badge_class = 'badge-education'; break;
                            case 'devops': $badge_class = 'badge-devops'; break;
                            case 'coding': $badge_class = 'badge-coding'; break;
                            case 'animals': $badge_class = 'badge-animals'; break;
                            case 'objects': $badge_class = 'badge-objects'; break;
                            case 'fruits': $badge_class = 'badge-fruits'; break;
                            case 'nature': $badge_class = 'badge-nature'; break;
                            case 'colors': $badge_class = 'badge-colors'; break;
                            case 'motivation': $badge_class = 'badge-motivation'; break;
                            case 'fun': $badge_class = 'badge-fun'; break;
                            default: $badge_class = 'badge-general';
                        }
                    ?>
                    <tr>
                        <td class="qa-question">
                            <?php echo htmlspecialchars($qa['question']); ?>
                            <?php if (($qa['source'] ?? '') === 'ai'): ?> <span class="pill">🤖 AI</span><?php endif; ?>
                            <?php if($qa['keywords']): ?>
                                <br><small style="color: #999;">🔑 <?php echo htmlspecialchars($qa['keywords']); ?></small>
                            <?php endif; ?>
                        </td>
                        <td class="qa-answer">
                            <?php echo nl2br(htmlspecialchars(mb_substr($qa['answer'], 0, 150))); ?>
                            <?php if(mb_strlen($qa['answer']) > 150) echo '...'; ?>
                        </td>
                        <td style="text-align: center;">
                            <span class="badge <?php echo $badge_class; ?>"><?php echo $qa['category']; ?></span>
                        </td>
                        <td style="text-align: center;"><?php echo $qa['usage_count']; ?></td>
                        <td class="qa-actions">
                            <a href="?edit=<?php echo $qa['id']; ?>" class="edit-btn">✏️ ویرایش</a>
                            <a href="?delete=<?php echo $qa['id']; ?>" class="delete-btn" onclick="return confirm('آیا از حذف این سوال و جواب مطمئن هستید؟')">🗑️ حذف</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    
    <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 15px; text-align: center;">
        <p>💡 <strong>نکته:</strong> برای دسته‌بندی‌های مختلف می‌توانید از دسته‌های زیر استفاده کنید:</p>
        <p>
            <span class="badge badge-general">general</span>
            <span class="badge badge-guide">guide</span>
            <span class="badge badge-info">info</span>
            <span class="badge badge-education">education</span>
            <span class="badge badge-devops">devops</span>
            <span class="badge badge-coding">coding</span>
            <span class="badge badge-animals">animals</span>
            <span class="badge badge-objects">objects</span>
            <span class="badge badge-fruits">fruits</span>
            <span class="badge badge-nature">nature</span>
            <span class="badge badge-colors">colors</span>
            <span class="badge badge-motivation">motivation</span>
            <span class="badge badge-fun">fun</span>
        </p>
    </div>
</div>
</body>
</html>