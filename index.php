<?php
// این فایل را با نام landing.php یا index.php در مسیر اصلی ذخیره کنید
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>کتابخانه راهنمای DevOps | ورود به سامانه</title>
  <style>
    /* حذف لینک فونت گوگل و استفاده از فونت سیستمی */
    body {
        font-family: 'Segoe UI', 'Tahoma', 'IranSans', 'Vazir', 'Shabnam', system-ui, -apple-system, BlinkMacSystemFont, 'Roboto', sans-serif;
    }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* انیمیشن پس‌زمینه */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            overflow: hidden;
        }
        
        .bg-animation div {
            position: absolute;
            display: block;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            animation: float 20s linear infinite;
        }
        
        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 0.5;
            }
            90% {
                opacity: 0.5;
            }
            100% {
                transform: translateY(-100vh) rotate(360deg);
                opacity: 0;
            }
        }
        
        .container {
            position: relative;
            z-index: 1;
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        /* هدر */
        .header {
            text-align: center;
            margin-bottom: 60px;
            animation: fadeInDown 0.8s ease;
        }
        
        .logo {
            font-size: 70px;
            margin-bottom: 20px;
            display: inline-block;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .header h1 {
            font-size: 3em;
            color: white;
            margin-bottom: 15px;
            text-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .header .highlight {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .header p {
            font-size: 1.2em;
            color: rgba(255,255,255,0.9);
            max-width: 600px;
            margin: 0 auto;
        }
        
        /* کارت‌های انتخاب */
        .cards-container {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
            margin-bottom: 60px;
        }
        
        .card {
            background: white;
            border-radius: 30px;
            padding: 40px 30px;
            width: 320px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            animation: fadeInUp 0.8s ease;
        }
        
        .card:first-child {
            animation-delay: 0.1s;
        }
        
        .card:last-child {
            animation-delay: 0.2s;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 50px rgba(0,0,0,0.2);
        }
        
        .card-icon {
            font-size: 70px;
            margin-bottom: 20px;
        }
        
        .card h2 {
            font-size: 1.8em;
            margin-bottom: 15px;
        }
        
        .card p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        
        .card .badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        /* کارت ادمین */
        .card-admin {
            border-top: 5px solid #ffc107;
        }
        
        .card-admin h2 {
            color: #ffc107;
        }
        
        .card-admin .badge {
            background: #ffc10720;
            color: #ffc107;
            border: 1px solid #ffc107;
        }
        
        /* کارت کاربر */
        .card-user {
            border-top: 5px solid #28a745;
        }
        
        .card-user h2 {
            color: #28a745;
        }
        
        .card-user .badge {
            background: #28a74520;
            color: #28a745;
            border: 1px solid #28a745;
        }
        
        /* ویژگی‌ها */
        .features {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
            margin-top: 40px;
            padding-top: 40px;
            border-top: 1px solid rgba(255,255,255,0.2);
        }
        
        .feature {
            text-align: center;
            color: white;
        }
        
        .feature-icon {
            font-size: 30px;
            margin-bottom: 10px;
        }
        
        .feature span {
            font-size: 14px;
            opacity: 0.8;
        }
        
        /* فوتر */
        .footer {
            text-align: center;
            margin-top: 60px;
            color: rgba(255,255,255,0.6);
            font-size: 14px;
        }
        
        /* دکمه بازگشت */
        .back-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
            z-index: 100;
        }
        
        .back-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }
        
        /* موج‌های تزئینی */
        .wave {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" fill-opacity="1" d="M0,192L48,197.3C96,203,192,213,288,208C384,203,480,181,576,165.3C672,149,768,139,864,144C960,149,1056,171,1152,176C1248,181,1344,171,1392,165.3L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') repeat-x;
            background-size: cover;
            z-index: 0;
            pointer-events: none;
        }
        
        /* ریسپانسیو */
        @media (max-width: 768px) {
            .container {
                padding: 20px 15px;
            }
            .header h1 {
                font-size: 2em;
            }
            .header p {
                font-size: 1em;
            }
            .card {
                width: 280px;
                padding: 30px 20px;
            }
            .card-icon {
                font-size: 50px;
            }
            .card h2 {
                font-size: 1.5em;
            }
            .features {
                gap: 20px;
            }
        }
        
        @media (max-width: 480px) {
            .cards-container {
                gap: 20px;
            }
            .card {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
    <!-- انیمیشن پس‌زمینه -->
    <div class="bg-animation" id="bgAnimation"></div>
    <div class="wave"></div>
    
    <div class="container">
        <div class="header">
            <div class="logo">📚</div>
            <h1>کتابخانه <span class="highlight">راهنمای DevOps</span></h1>
            <p>مرجع کامل دستورات لینوکس، داکر، گیت، Kubernetes و ابزارهای مدرن</p>
        </div>
        
        <div class="cards-container">
            <!-- کارت مدیریت (ادمین) -->
            <a href="admin/index.php" class="card card-admin">
                <div class="card-icon">👑</div>
                <h2>ورود به پنل مدیریت</h2>
                <p>افزودن دستورات جدید، ویرایش، حذف، مدیریت دسته‌بندی‌ها و آپلود فایل</p>
                <div class="badge">🔐 دسترسی کامل</div>
            </a>
            
            <!-- کارت کاربر (عمومی) -->
            <a href="user/index_readonly.php" class="card card-user">
                <div class="card-icon">👤</div>
                <h2>ورود به نسخه عمومی</h2>
                <p>جستجو، مشاهده و کپی دستورات - فقط خواندنی</p>
                <div class="badge">🔍 دسترسی محدود</div>
            </a>
        </div>
        
        <div class="features">
            <div class="feature">
                <div class="feature-icon">🐧</div>
                <span>دستورات لینوکس</span>
            </div>
            <div class="feature">
                <div class="feature-icon">🐳</div>
                <span>دستورات داکر</span>
            </div>
            <div class="feature">
                <div class="feature-icon">📝</div>
                <span>دستورات گیت</span>
            </div>
            <div class="feature">
                <div class="feature-icon">☸️</div>
                <span>Kubernetes</span>
            </div>
            <div class="feature">
                <div class="feature-icon">🌐</div>
                <span>شبکه</span>
            </div>
            <div class="feature">
                <div class="feature-icon">🤖</div>
                <span>چت‌بات هوشمند</span>
            </div>
        </div>
        
        <div class="footer">
            <p>✨ کتابخانه راهنمای DevOps | ساخته شده با ❤️ برای جامعه برنامه‌نویسان ایران</p>
            <p style="font-size: 12px; margin-top: 8px;">نسخه 2.0 | تمامی حقوق محفوظ است</p>
        </div>
    </div>
    
    <a href="#" class="back-btn" onclick="window.scrollTo({top:0,behavior:'smooth'}); return false;">↑ برگشت به بالا</a>
    
    <script>
        // ساخت حباب‌های متحرک پس‌زمینه
        const bgAnimation = document.getElementById('bgAnimation');
        const numBubbles = 30;
        
        for (let i = 0; i < numBubbles; i++) {
            const bubble = document.createElement('div');
            const size = Math.random() * 100 + 30;
            const duration = Math.random() * 15 + 10;
            const delay = Math.random() * 10;
            const leftPos = Math.random() * 100;
            
            bubble.style.width = size + 'px';
            bubble.style.height = size + 'px';
            bubble.style.left = leftPos + '%';
            bubble.style.bottom = '-' + size + 'px';
            bubble.style.animationDuration = duration + 's';
            bubble.style.animationDelay = delay + 's';
            bubble.style.opacity = Math.random() * 0.3 + 0.1;
            
            bgAnimation.appendChild(bubble);
        }
        
        // اسکرول نرم به بالا
        document.querySelector('.back-btn').addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
</body>
</html>