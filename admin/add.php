<?php
require_once 'config.php';
require_admin($pdo); // v3: فقط ادمین

$success = '';
$error = '';

// پوشه ذخیره فایل‌ها
$upload_dir = 'uploads/';
if(!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// تابع اطمینان از وجود دسته‌بندی
function ensureCategoryExists($category, $pdo) {
    if(empty($category)) $category = 'Other';
    
    $check = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE category = ?");
    $check->execute([$category]);
    
    if($check->fetchColumn() == 0) {
        $insert = $pdo->prepare("INSERT INTO categories (category) VALUES (?)");
        $insert->execute([$category]);
        return true;
    }
    return false;
}

// تابع ذخیره فایل پیوست
function saveAttachment($file, $command_id) {
    global $upload_dir, $pdo;
    
    $allowed_extensions = ['pdf', 'txt', 'jpg', 'jpeg', 'png', 'gif', 'mp4', 'mkv', 'webm', 'yml', 'yaml', 'json', 'md', 'doc', 'docx', 'xls', 'xlsx'];
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $file_size = $file['size'];
    $original_name = $file['name'];
    
    if($file_size > 50 * 1024 * 1024) {
        return ['error' => 'حجم فایل نباید بیشتر از 50 مگابایت باشد!'];
    }
    
    if(!in_array($file_extension, $allowed_extensions)) {
        return ['error' => 'فرمت فایل پشتیبانی نمی‌شود!'];
    }
    
    $new_filename = time() . '_' . uniqid() . '.' . $file_extension;
    $file_path = $upload_dir . $new_filename;
    
    if(move_uploaded_file($file['tmp_name'], $file_path)) {
        $stmt = $pdo->prepare("INSERT INTO command_files (command_id, file_name, file_type, file_size, file_path) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$command_id, $original_name, $file_extension, $file_size, $file_path]);
        return ['success' => true];
    }
    
    return ['error' => 'خطا در ذخیره فایل!'];
}

// تابع پردازش فایل حاوی دستورات
function processCommandsFile($file, $pdo) {
    global $upload_dir;
    
    $allowed_extensions = ['json', 'txt', 'yml', 'yaml'];
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if($file['size'] > 5 * 1024 * 1024) {
        return ['error' => 'حجم فایل نباید بیشتر از 5 مگابایت باشد!'];
    }
    
    if(!in_array($file_extension, $allowed_extensions)) {
        return ['error' => 'فرمت فایل پشتیبانی نمی‌شود.'];
    }
    
    $content = file_get_contents($file['tmp_name']);
    $commands_data = [];
    
    if($file_extension == 'json') {
        $json_data = json_decode($content, true);
        if(json_last_error() !== JSON_ERROR_NONE) {
            return ['error' => 'فرمت JSON نامعتبر است!'];
        }
        if(isset($json_data[0]['command'])) {
            $commands_data = $json_data;
        } elseif(isset($json_data['commands'])) {
            $commands_data = $json_data['commands'];
        } elseif(isset($json_data['command'])) {
            $commands_data = [$json_data];
        }
    }
    elseif($file_extension == 'yml' || $file_extension == 'yaml') {
        $lines = explode("\n", $content);
        $current_cmd = [];
        foreach($lines as $line) {
            $line = trim($line);
            if(empty($line) || $line[0] == '#') continue;
            
            if(preg_match('/^[-]?\s*command:\s*(.+)$/i', $line, $matches)) {
                if(!empty($current_cmd)) $commands_data[] = $current_cmd;
                $current_cmd = [];
                $current_cmd['command'] = trim($matches[1]);
            }
            elseif(preg_match('/^[-]?\s*category:\s*(.+)$/i', $line, $matches)) {
                $current_cmd['category'] = trim($matches[1]);
            }
            elseif(preg_match('/^[-]?\s*description:\s*(.+)$/i', $line, $matches)) {
                $current_cmd['description'] = trim($matches[1]);
            }
            elseif(preg_match('/^[-]?\s*keywords:\s*(.+)$/i', $line, $matches)) {
                $current_cmd['keywords'] = trim($matches[1]);
            }
            elseif(preg_match('/^[-]?\s*example:\s*(.+)$/i', $line, $matches)) {
                $current_cmd['example'] = trim($matches[1]);
            }
            elseif(preg_match('/^[-]?\s*(?:similar|similar_commands):\s*(.+)$/i', $line, $matches)) {
                $current_cmd['similar_commands'] = trim($matches[1]);
            }
        }
        if(!empty($current_cmd)) $commands_data[] = $current_cmd;
    }
    elseif($file_extension == 'txt') {
        $lines = explode("\n", $content);
        foreach($lines as $line) {
            $line = trim($line);
            if(!empty($line)) {
                $commands_data[] = [
                    'command' => $line,
                    'category' => 'Other',
                    'description' => 'دستور وارد شده از فایل متنی',
                    'keywords' => '',
                    'example' => '',
                    'similar_commands' => ''
                ];
            }
        }
    }
    
    if(empty($commands_data)) {
        return ['error' => 'هیچ دستور معتبری در فایل یافت نشد!'];
    }
    
    return ['success' => true, 'data' => $commands_data];
}

// پردازش فرم
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // آپلود فایل حاوی دستورات
    if(isset($_FILES['batch_file']) && $_FILES['batch_file']['error'] == UPLOAD_ERR_OK) {
        $result = processCommandsFile($_FILES['batch_file'], $pdo);
        
        if(isset($result['error'])) {
            $error = $result['error'];
        } else {
            $commands_data = $result['data'];
            $added_count = 0;
            $failed_count = 0;
            
            foreach($commands_data as $cmd_data) {
                $category = isset($cmd_data['category']) && !empty($cmd_data['category']) ? $cmd_data['category'] : 'Other';
                $command = isset($cmd_data['command']) ? trim($cmd_data['command']) : '';
                $description = isset($cmd_data['description']) ? trim($cmd_data['description']) : 'دستور: ' . $command;
                $keywords = isset($cmd_data['keywords']) ? $cmd_data['keywords'] : '';
                $example = isset($cmd_data['example']) ? $cmd_data['example'] : '';
                $similar = isset($cmd_data['similar_commands']) ? $cmd_data['similar_commands'] : '';
                
                if(!empty($command)) {
                    // اطمینان از وجود دسته‌بندی
                    ensureCategoryExists($category, $pdo);
                    
                    $check = $pdo->prepare("SELECT COUNT(*) FROM commands WHERE command = ? AND category = ?");
                    $check->execute([$command, $category]);
                    
                    if($check->fetchColumn() == 0) {
                        $stmt = $pdo->prepare("INSERT INTO commands (category, command, description, keywords, example, similar_commands) VALUES (?, ?, ?, ?, ?, ?)");
                        if($stmt->execute([$category, $command, $description, $keywords, $example, $similar])) {
                            $added_count++;
                        } else {
                            $failed_count++;
                        }
                    } else {
                        $failed_count++;
                    }
                } else {
                    $failed_count++;
                }
            }
            
            if($added_count > 0) {
                $success = "✅ $added_count دستور با موفقیت از فایل اضافه شد!";
                if($failed_count > 0) {
                    $error = "⚠️ $failed_count دستور تکراری یا نامعتبر بودند.";
                }
            } else {
                $error = "❌ هیچ دستور جدیدی از فایل اضافه نشد!";
            }
        }
    } 
    // افزودن دستور جدید با فایل پیوست
    elseif(isset($_POST['add_command'])) {
        $category = trim($_POST['category']);
        $command = trim($_POST['command']);
        $description = trim($_POST['description']);
        $keywords = trim($_POST['keywords']);
        $example = trim($_POST['example']);
        $similar = trim($_POST['similar_commands']);
        
        if(empty($category) || empty($command) || empty($description)) {
            $error = "❌ لطفاً حداقل دسته‌بندی، دستور و توضیحات را پر کنید!";
        } else {
            // اطمینان از وجود دسته‌بندی
            ensureCategoryExists($category, $pdo);
            
            $check = $pdo->prepare("SELECT COUNT(*) FROM commands WHERE command = ? AND category = ?");
            $check->execute([$command, $category]);
            
            if($check->fetchColumn() > 0) {
                $error = "❌ این دستور قبلاً در این دسته‌بندی وجود دارد!";
            } else {
                try {
                    $sql = "INSERT INTO commands (category, command, description, keywords, example, similar_commands) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    
                    if($stmt->execute([$category, $command, $description, $keywords, $example, $similar])) {
                        $command_id = $pdo->lastInsertId();
                        
                        $attached_count = 0;
                        if(!empty($_FILES['attachments']['name'][0])) {
                            for($i = 0; $i < count($_FILES['attachments']['name']); $i++) {
                                if($_FILES['attachments']['error'][$i] == UPLOAD_ERR_OK) {
                                    $file = [
                                        'name' => $_FILES['attachments']['name'][$i],
                                        'tmp_name' => $_FILES['attachments']['tmp_name'][$i],
                                        'size' => $_FILES['attachments']['size'][$i],
                                        'error' => $_FILES['attachments']['error'][$i]
                                    ];
                                    $file_result = saveAttachment($file, $command_id);
                                    if(isset($file_result['success'])) $attached_count++;
                                }
                            }
                        }
                        
                        if($attached_count > 0) {
                            $success = "✅ دستور با موفقیت اضافه شد و $attached_count فایل پیوست شد!";
                        } else {
                            $success = "✅ دستور با موفقیت اضافه شد!";
                        }
                        
                        $_POST = [];
                    } else {
                        $error = "❌ خطا در افزودن دستور";
                    }
                } catch(PDOException $e) {
                    $error = "❌ خطا: " . $e->getMessage();
                }
            }
        }
    }
}

$categories = $pdo->query("SELECT category FROM categories ORDER BY category")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>افزودن دستور جدید</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../assets/css/luxury.css?v=3.1">
    <style>
        .section-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .section-title {
            color: #667eea;
            border-right: 4px solid #667eea;
            padding-right: 15px;
            margin-bottom: 20px;
            font-size: 1.3em;
        }
        
        .upload-area {
            border: 2px dashed #667eea;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            background: #f8f9ff;
        }
        
        .file-label {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 12px 30px;
            border-radius: 30px;
            cursor: pointer;
            display: inline-block;
            transition: all 0.3s;
        }
        
        .batch-label {
            background: #17a2b8;
        }
        
        .attachment-label {
            background: #28a745;
        }
        
        .file-info {
            margin-top: 15px;
            font-size: 12px;
            color: #666;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            margin: 5px;
            background: #e3f2fd;
            color: #1976d2;
        }
        
        .selected-files {
            margin-top: 15px;
            padding: 10px;
            background: #e8f0fe;
            border-radius: 8px;
            font-size: 12px;
        }
        
        .separator {
            text-align: center;
            margin: 30px 0;
            position: relative;
        }
        
        .separator::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e0e0e0;
        }
        
        .separator span {
            background: white;
            padding: 0 20px;
            color: #999;
            position: relative;
            z-index: 1;
        }
        
        .help-text {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
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
            border-radius: 8px;
            font-size: 14px;
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
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>➕ افزودن دستور جدید</h1>
            <p>می‌توانید دستور را دستی وارد کنید یا از فایل آپلود کنید</p>
            <div class="nav-menu">
                <a href="index.php" class="nav-btn">🏠 صفحه اصلی</a>
                <a href="add.php" class="nav-btn">➕ افزودن دستور</a>
                <a href="search.php" class="nav-btn">🔍 جستجو</a>
                <a href="manage_categories.php" class="nav-btn">🏷️ مدیریت دسته‌بندی</a>
                <a href="users.php" class="nav-btn">👥 کاربران</a>
                <a href="change_password.php" class="nav-btn">🔑 رمز عبور</a>
                <a href="../logout.php" class="nav-btn">🚪 خروج</a>
            </div>
        </div>

        <?php if($success): ?>
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <?php if($error): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- بخش 1: آپلود فایل حاوی دستورات -->
        <div class="section-card">
            <h3 class="section-title">📦 آپلود فایل حاوی دستورات (دسته‌جمعی)</h3>
            <div class="upload-area">
                <form method="POST" enctype="multipart/form-data">
                    <input type="file" name="batch_file" id="batch_file" accept=".json,.txt,.yml,.yaml" style="display:none;" onchange="this.form.submit()">
                    <label for="batch_file" class="file-label batch-label">📂 انتخاب فایل JSON / YAML / TXT</label>
                    <div class="file-info">
                        <span class="badge">JSON</span>
                        <span class="badge">YAML/YML</span>
                        <span class="badge">TXT</span>
                        <br>
                        <strong>حداکثر حجم:</strong> 5 مگابایت
                    </div>
                </form>
            </div>
        </div>

        <div class="separator">
            <span>یا</span>
        </div>

        <!-- بخش 2: افزودن دستور جدید -->
        <div class="section-card">
            <h3 class="section-title">✏️ افزودن دستور جدید</h3>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>📁 دسته‌بندی:</label>
                    <select name="category" required>
                        <option value="">انتخاب کنید...</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat['category']); ?>">
                                <?php echo htmlspecialchars($cat['category']); ?>
                            </option>
                        <?php endforeach; ?>
                        <option value="Other">سایر (ساخت جدید)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>💻 دستور:</label>
                    <input type="text" name="command" required placeholder="مثال: docker ps -a">
                </div>

                <div class="form-group">
                    <label>📖 توضیحات:</label>
                    <textarea name="description" required placeholder="توضیح کامل دستور..."></textarea>
                </div>

                <div class="form-group">
                    <label>🔑 کلمات کلیدی:</label>
                    <input type="text" name="keywords" placeholder="مثال: container, list">
                </div>

                <div class="form-group">
                    <label>💡 مثال کاربردی:</label>
                    <textarea name="example" placeholder="مثال استفاده از دستور..."></textarea>
                </div>

                <div class="form-group">
                    <label>🔄 دستورات مشابه:</label>
                    <input type="text" name="similar_commands" placeholder="مثال: docker ps -a, docker stats">
                </div>

                <div class="form-group">
                    <label>📎 فایل‌های پیوست (اختیاری):</label>
                    <input type="file" name="attachments[]" id="attachments" multiple accept=".pdf,.txt,.jpg,.jpeg,.png,.gif,.mp4,.yml,.yaml,.json,.md">
                    <div class="help-text">فرمت‌های مجاز: PDF, JPG, PNG, MP4, YAML, JSON, MD - حداکثر 50 مگابایت</div>
                    <div id="selectedFiles" class="selected-files" style="display:none;"></div>
                </div>

                <button type="submit" name="add_command" class="submit-btn">💾 ذخیره دستور</button>
            </form>
        </div>
    </div>

    <script>
    document.getElementById('attachments').addEventListener('change', function(e) {
        const files = e.target.files;
        const selectedDiv = document.getElementById('selectedFiles');
        
        if(files.length > 0) {
            let html = '📎 فایل‌های انتخاب شده:<br>';
            for(let i = 0; i < files.length; i++) {
                html += `• ${files[i].name} (${(files[i].size / 1024).toFixed(2)} KB)<br>`;
            }
            selectedDiv.innerHTML = html;
            selectedDiv.style.display = 'block';
        } else {
            selectedDiv.style.display = 'none';
        }
    });


    // اضافه کن به قسمت processCommandsFile
function cleanCategory($category) {
    // حذف کوتیشن‌های اضافی از اول و آخر
    $category = trim($category);
    $category = preg_replace('/^["\'](.*)["\']$/', '$1', $category);
    return $category;
}

// و در جایی که category رو میخونی:
$category = isset($cmd_data['category']) ? cleanCategory($cmd_data['category']) : 'Other';


    </script>
</body>
</html>