<?php
require_once __DIR__ . '/../config.php';
require_admin($pdo); // v3: فقط ادمین

$success = '';
$error = '';

// اضافه کردن جواب به سوال بی‌جواب و ذخیره در chatbot_qa
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_answer'])) {
    $unknown_id = (int)$_POST['unknown_id'];
    $question = trim($_POST['question'] ?? '');
    $answer = trim($_POST['answer'] ?? '');
    $keywords = trim($_POST['keywords'] ?? '');
    $category = trim($_POST['category'] ?? 'general');

    
    if (!empty($answer)) {
        // اضافه کردن به جدول chatbot_qa
        $stmt = $pdo->prepare("INSERT INTO chatbot_qa (question, answer, keywords, category) VALUES (?, ?, ?, ?)");
        $stmt->execute([$question, $answer, $keywords, $category]);
        
        // به‌روزرسانی وضعیت سوال بی‌جواب به answered
        $update = $pdo->prepare("UPDATE chatbot_unknown_questions SET status = 'answered' WHERE id = ?");
        $update->execute([$unknown_id]);
        
        $success = "✅ جواب برای سوال «$question» اضافه شد! چت‌بات از این به بعد این سوال رو بلده.";
    } else {
        $error = "❌ لطفاً جواب را وارد کنید!";
    }
}

// نادیده گرفتن سوال
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("UPDATE chatbot_unknown_questions SET status = 'ignored' WHERE id = ?");
    $stmt->execute([$id]);
    $success = "✅ سوال نادیده گرفته شد.";
}

// حذف کامل سوال
if (isset($_GET['permanent_delete'])) {
    $id = (int)$_GET['permanent_delete'];
    $stmt = $pdo->prepare("DELETE FROM chatbot_unknown_questions WHERE id = ?");
    $stmt->execute([$id]);
    $success = "✅ سوال حذف شد.";
}

$questions = $pdo->query("SELECT * FROM chatbot_unknown_questions WHERE status = 'pending' ORDER BY asked_count DESC, last_asked DESC")->fetchAll();
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>مدیریت سوالات بی‌جواب چت‌بات</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../assets/css/luxury.css?v=3.1">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 900px;
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
            margin-bottom: 10px;
        }
        .nav-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 15px;
        }
        .nav-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            text-decoration: none;
            transition: transform 0.3s;
            display: inline-block;
        }
        .nav-btn:hover {
            transform: translateY(-2px);
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            border-right: 4px solid #28a745;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            border-right: 4px solid #dc3545;
        }
        .question-card {
            background: #fff3cd;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 25px;
            border-right: 5px solid #ffc107;
            transition: all 0.3s;
        }
        .question-card:hover {
            transform: translateX(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }
        .question-text {
            font-weight: bold;
            font-size: 1.15em;
            color: #856404;
            background: #fff3cd;
            padding: 8px 15px;
            border-radius: 25px;
            display: inline-block;
        }
        .question-meta {
            color: #666;
            font-size: 12px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        .answer-form {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px dashed #ffc107;
        }
        .form-group {
            margin-bottom: 12px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
            font-size: 13px;
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        .form-group input:focus, .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn-submit {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
        }
        .btn-submit:hover {
            transform: scale(1.02);
        }
        .btn-delete {
            background: #dc3545;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 20px;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
            display: inline-block;
        }
        .btn-ignore {
            background: #6c757d;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 20px;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
            display: inline-block;
        }
        .btn-delete:hover, .btn-ignore:hover {
            transform: scale(1.02);
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            flex-wrap: wrap;
        }
        .empty-state {
            background: #d4edda;
            padding: 50px;
            text-align: center;
            border-radius: 20px;
        }
        .empty-state p {
            font-size: 1.2em;
            margin-bottom: 15px;
        }
        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #e0e0e0;
        }
        @media (max-width: 600px) {
            .container { padding: 20px; }
            .question-header { flex-direction: column; align-items: flex-start; gap: 10px; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>📚 مدیریت سوالات بی‌جواب چت‌بات</h1>
        <p>سوالاتی که کاربران پرسیدن و چت‌بات جواب نداشته - اینجا میتونی جواب بدی</p>
        <div class="nav-buttons">
            <a href="chatbot.php" class="nav-btn">🤖 رفتن به چت‌بات</a>
            <a href="manage_qa.php" class="nav-btn">📋 مدیریت QA</a>
            <a href="../index.php" class="nav-btn">🏠 صفحه اصلی</a>
        </div>
    </div>
    
    <?php if($success): ?>
        <div class="alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if($error): ?>
        <div class="alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if(count($questions) == 0): ?>
        <div class="empty-state">
            <p>🎉 عالی!</p>
            <p>هیچ سوال بی‌جوابی وجود نداره. چت‌بات همه چیز رو بلده!</p>
            <p style="font-size: 14px; margin-top: 15px;">✨ کاربران سوالات جدید بپرسن، اینجا نمایش داده میشه.</p>
        </div>
    <?php else: ?>
        <h3>📋 سوالات بی‌جواب (<?php echo count($questions); ?> مورد)</h3>
        <p style="color: #666; margin-bottom: 20px; font-size: 13px;">💡 برای هر سوال، جواب مناسب رو وارد کن تا چت‌بات یاد بگیره.</p>
        
        <?php foreach($questions as $q): ?>
            <div class="question-card">
                <div class="question-header">
                    <div class="question-text">❓ <?php echo htmlspecialchars($q['question']); ?></div>
                    <div class="question-meta">
                        <span>📊 تعداد دفعات پرسش: <?php echo $q['asked_count']; ?> بار</span>
                        <span>🕐 آخرین بار: <?php echo date('Y-m-d H:i', strtotime($q['last_asked'])); ?></span>
                        <span>📅 اولین بار: <?php echo date('Y-m-d', strtotime($q['first_asked'])); ?></span>
                    </div>
                </div>
                
                <form method="POST" class="answer-form">
                    <input type="hidden" name="unknown_id" value="<?php echo $q['id']; ?>">
                    <input type="hidden" name="question" value="<?php echo htmlspecialchars($q['question']); ?>">
                    
                    <div class="form-group">
                        <label>💬 جواب مناسب برای این سوال:</label>
                        <textarea name="answer" rows="3" placeholder="مثال: 👋 سلام عزیزم! چطور میتونم کمکت کنم؟" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>🔑 کلمات کلیدی (با کاما جدا کنید - اختیاری):</label>
                        <input type="text" name="keywords" placeholder="مثال: سلام, سلامتی, علیک" value="<?php echo htmlspecialchars($q['question']); ?>">
                        <small style="color: #999;">این کلمات به تشخیص بهتر سوال کمک میکنند</small>
                    </div>
                    
                    <div class="form-group">
                        <label>📂 دسته‌بندی:</label>
                        <select name="category">
                            <option value="general">general (عمومی)</option>
                            <option value="guide">guide (راهنما)</option>
                            <option value="info">info (اطلاعاتی)</option>
                            <option value="education">education (آموزشی)</option>
                            <option value="devops">devops (دواپس)</option>
                            <option value="coding">coding (برنامه‌نویسی)</option>
                        </select>
                    </div>
                    
                    <div class="action-buttons">
                        <button type="submit" name="add_answer" class="btn-submit">✅ ذخیره جواب (چت‌بات یاد میگیره)</button>
                        <a href="?delete=<?php echo $q['id']; ?>" class="btn-ignore" onclick="return confirm('این سوال نادیده گرفته شود؟')">🙈 نادیده گرفتن</a>
                        <a href="?permanent_delete=<?php echo $q['id']; ?>" class="btn-delete" onclick="return confirm('حذف شود؟')">🗑️ حذف</a>
                    </div>
                </form>
            </div>
        <?php endforeach; ?>
        
        <hr>
        <div style="background: #e8f0fe; padding: 15px; border-radius: 12px; margin-top: 10px;">
            <p style="margin: 0; font-size: 13px;">💡 <strong>نکته:</strong> بعد از ذخیره جواب، چت‌بات از این به بعد این سوال رو بلد خواهد بود و سوال از لیست بی‌جواب حذف میشه.</p>
        </div>
    <?php endif; ?>
</div>
</body>
</html>