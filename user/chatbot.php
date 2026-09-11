<?php
session_start();
require_once __DIR__ . '/../config.php';

// پردازش درخواست AJAX
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (isset($_POST['question'])) {
        $question = trim($_POST['question']);
        $results = [];
        $answer = '';
        $is_qa = false;
        
        // ========== بخش 1: سوال و جواب از جدول chatbot_qa ==========
        $stmt = $pdo->prepare("SELECT * FROM chatbot_qa WHERE question LIKE ? OR keywords LIKE ? ORDER BY usage_count DESC LIMIT 1");
        $searchTerm = "%$question%";
        $stmt->execute([$searchTerm, $searchTerm]);
        $qa_result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($qa_result) {
            // آپدیت تعداد استفاده
            $update = $pdo->prepare("UPDATE chatbot_qa SET usage_count = usage_count + 1 WHERE id = ?");
            $update->execute([$qa_result['id']]);
            
            $answer = $qa_result['answer'];
            $is_qa = true;
        }
        
        // ========== بخش 2: اگر جواب QA پیدا نشد، برو سراغ دستورات ==========
        if (!$is_qa) {
            $question_lower = strtolower($question);
            
            // دریافت همه دسته‌بندی‌ها
            $all_categories = $pdo->query("SELECT category FROM categories ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
            
            $matched_category = null;
            foreach ($all_categories as $cat) {
                if (strpos($question_lower, strtolower($cat)) !== false) {
                    $matched_category = $cat;
                    break;
                }
            }
            
            // کلمات کلیدی مخصوص برای تطابق بهتر
            $special_keywords = [
                'postgresql' => 'PostgreSQL', 'postgres' => 'PostgreSQL', 'پستگرس' => 'PostgreSQL',
                'mongodb' => 'MongoDB', 'mongo' => 'MongoDB', 'مونگو' => 'MongoDB',
                'redis' => 'Redis', 'ردیس' => 'Redis',
                'docker' => 'Docker', 'داکر' => 'Docker',
                'linux' => 'Linux', 'لینوکس' => 'Linux',
                'git' => 'Git', 'گیت' => 'Git',
                'kubernetes' => 'Kubernetes', 'k8s' => 'Kubernetes', 'کوبرنتیز' => 'Kubernetes'
            ];
            
            foreach ($special_keywords as $key => $cat) {
                if (strpos($question_lower, $key) !== false) {
                    $matched_category = $cat;
                    break;
                }
            }
            
            // اگر دسته‌بندی مشخص شده باشد
            if ($matched_category) {
                $stmt = $pdo->prepare("SELECT id, command, description FROM commands WHERE category = ? ORDER BY command");
                $stmt->execute([$matched_category]);
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                $icons = [
                    'Linux' => '🐧', 'Docker' => '🐳', 'Git' => '📝',
                    'Kubernetes' => '☸️', 'Network' => '🌐', 'PostgreSQL' => '🐘',
                    'MongoDB' => '🍃', 'Redis' => '📀', 'Nginx' => '⚡',
                    'Ansible' => '🤖', 'Terraform' => '🏗️'
                ];
                $icon = $icons[$matched_category] ?? '📁';
                $answer = "{$icon} همه دستورات {$matched_category}: (" . count($results) . " دستور)";
            } 
            // جستجوی عمومی
            else {
                $stmt = $pdo->prepare("SELECT id, command, description FROM commands 
                    WHERE command LIKE ? OR description LIKE ? OR keywords LIKE ? 
                    ORDER BY command LIMIT 30");
                $searchTerm = "%$question%";
                $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if(count($results) > 0) {
                    $answer = '🔍 نتایج جستجو برای "' . htmlspecialchars($question) . '": (' . count($results) . ' دستور)';
                } else {
                    $categories_list = implode('", "', $all_categories);
                    $answer = '🤔 دستوری با این کلمات پیدا نکردم.\n\n📂 دسته‌بندی‌های موجود:\n"' . $categories_list . '"\n\n✨ مثال: "دستورات داکر" یا "docker ps"';
                }
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode(['answer' => $answer, 'commands' => $results]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>🤖 دستیار هوشمند DevOps</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .chat-card {
            max-width: 800px;
            width: 100%;
            background: white;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        .chat-head {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }
        .chat-head h2 { margin-bottom: 5px; font-size: 1.5em; }
        .chat-head p { opacity: 0.9; font-size: 0.9em; }
        .home-btn {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.2);
            padding: 5px 12px;
            border-radius: 20px;
            text-decoration: none;
            color: white;
            font-size: 12px;
        }
        .home-btn:hover { background: rgba(255,255,255,0.3); }
        .chat-body {
            height: 480px;
            overflow-y: auto;
            padding: 20px;
            background: #f4f6f9;
        }
        .msg {
            margin-bottom: 12px;
            padding: 10px 16px;
            border-radius: 18px;
            max-width: 90%;
            clear: both;
            word-wrap: break-word;
        }
        .user {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            float: left;
            border-bottom-left-radius: 5px;
        }
        .bot {
            background: white;
            color: #333;
            float: right;
            border-bottom-right-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .cmd-item {
            background: #eef2ff;
            margin-top: 8px;
            padding: 10px 12px;
            border-radius: 12px;
            transition: all 0.2s;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .cmd-item:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }
        .cmd-item:hover .cmd-text,
        .cmd-item:hover .cmd-desc {
            color: white;
        }
        .cmd-text {
            flex: 2;
            font-weight: bold;
            font-family: monospace;
            font-size: 13px;
        }
        .cmd-desc {
            flex: 3;
            font-size: 12px;
            color: #666;
        }
        .cmd-buttons {
            display: flex;
            gap: 6px;
        }
        .cmd-copy, .cmd-detail {
            background: rgba(0,0,0,0.1);
            border: none;
            padding: 5px 10px;
            border-radius: 15px;
            cursor: pointer;
            font-size: 11px;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        .cmd-copy { background: #28a745; color: white; }
        .cmd-detail { background: #667eea; color: white; }
        .cmd-item:hover .cmd-copy,
        .cmd-item:hover .cmd-detail {
            transform: scale(1.05);
        }
        .chat-foot {
            display: flex;
            padding: 15px;
            background: white;
            border-top: 1px solid #ddd;
            gap: 10px;
        }
        .chat-foot input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 30px;
            font-size: 14px;
        }
        .chat-foot input:focus {
            outline: none;
            border-color: #667eea;
        }
        .chat-foot button {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 30px;
            cursor: pointer;
            font-weight: bold;
            transition: transform 0.2s;
        }
        .chat-foot button:hover { transform: scale(1.02); }
        .clear { clear: both; }
        
        .categories-cloud {
            display: flex;
            gap: 8px;
            padding: 10px 15px;
            background: #f0f0f0;
            border-bottom: 1px solid #ddd;
            flex-wrap: wrap;
            max-height: 100px;
            overflow-y: auto;
        }
        .cat-btn {
            background: #e0e0e0;
            border: none;
            padding: 5px 12px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.2s;
        }
        .cat-btn:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        @media (max-width: 600px) {
            .cmd-item { flex-direction: column; align-items: stretch; }
            .cmd-buttons { justify-content: flex-end; }
            .msg { max-width: 95%; }
        }
    </style>
</head>
<body>
<div class="chat-card">
    <div class="chat-head">
        <a href="../index.php" class="home-btn">🔙 خانه</a>
        <h2>🤖 دستیار هوشمند DevOps</h2>
        <p>باهوش مثل دیپ‌سیک! ✨</p>
    </div>
    <div class="categories-cloud" id="categoriesCloud">
        <button class="cat-btn" disabled>⏳ بارگذاری...</button>
    </div>
    <div class="chat-body" id="chatBody">
        <div class="msg bot">
            👋 سلام عزیزم! من دستیار هوشمند DevOps هستم.<br><br>
            ✨ میتونم:<br>
            • به سوالات عادی جواب بدم: <strong>"سلام" , "چطوری" , "ممنون"</strong><br>
            • دستورات یک دسته رو بهت بدم: <strong>"دستورات داکر"</strong><br>
            • کلمه کلیدی جستجو کنم: <strong>"ps" یا "container"</strong><br>
            • درباره ابزارها باهات حرف بزنم: <strong>"postgresql", "kubernetes"</strong><br><br>
            💡 هر دستور دو دکمه داره: 📋 کپی | 🔍 جزئیات
        </div>
        <div class="clear"></div>
    </div>
    <div class="chat-foot">
        <input type="text" id="question" placeholder="مثلاً: سلام, دستورات داکر, docker ps, postgresql ...">
        <button onclick="sendMsg()">📨 ارسال</button>
    </div>
</div>

<script>
async function loadCategories() {
    try {
        let res = await fetch('../get_categories.php');
        let categories = await res.json();
        let container = document.getElementById('categoriesCloud');
        container.innerHTML = '';
        categories.forEach(cat => {
            let btn = document.createElement('button');
            btn.className = 'cat-btn';
            btn.innerHTML = cat;
            btn.onclick = () => {
                document.getElementById('question').value = 'دستورات ' + cat;
                sendMsg();
            };
            container.appendChild(btn);
        });
    } catch(e) {
        document.getElementById('categoriesCloud').innerHTML = '<button class="cat-btn">📁 دسته‌بندی‌ها</button>';
    }
}

async function sendMsg() {
    let inp = document.getElementById('question');
    let q = inp.value.trim();
    if (!q) return alert('لطفاً سوال خود را بنویسید!');
    
    addMsg(q, 'user');
    inp.value = '';
    
    try {
        let res = await fetch(window.location.href, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'question=' + encodeURIComponent(q)
        });
        let data = await res.json();
        
        let html = data.answer;
        if (data.commands && data.commands.length) {
            data.commands.forEach(cmd => {
                let desc = cmd.description ? cmd.description.substring(0, 80) : '';
                html += `<div class="cmd-item">
                    <span class="cmd-text">📋 ${escapeHtml(cmd.command)}</span>
                    <span class="cmd-desc">${escapeHtml(desc)}...</span>
                    <span class="cmd-buttons">
                        <button class="cmd-copy" onclick="event.stopPropagation(); copyIt('${escapeHtml(cmd.command)}')">📋 کپی</button>
                        <a href="../command_detail.php?id=${cmd.id}" target="_blank" class="cmd-detail" onclick="event.stopPropagation()">🔍 جزئیات</a>
                    </span>
                </div>`;
            });
        }
        addMsg(html || '❌ پاسخی دریافت نشد.', 'bot');
    } catch(e) {
        addMsg('⚠️ خطا در ارتباط با سرور', 'bot');
    }
}

function addMsg(txt, type) {
    let body = document.getElementById('chatBody');
    let d = document.createElement('div');
    d.className = 'msg ' + type;
    d.innerHTML = txt;
    body.appendChild(d);
    let cl = document.createElement('div');
    cl.className = 'clear';
    body.appendChild(cl);
    body.scrollTop = body.scrollHeight;
}

function copyIt(cmd) {
    navigator.clipboard.writeText(cmd);
    alert('✅ کپی شد: ' + cmd);
}

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

document.getElementById('question').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') sendMsg();
});

loadCategories();
</script>
</body>
</html>