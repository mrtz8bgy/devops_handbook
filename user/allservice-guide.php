
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>راهنمای کامل سرویس‌های Docker - آموزش DevOps</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Vazir', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 1.2em;
            opacity: 0.9;
        }
        
        .nav {
            background: #2d3748;
            padding: 15px;
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }
        
        .nav a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 5px;
            transition: background 0.3s;
            font-size: 14px;
        }
        
        .nav a:hover {
            background: #667eea;
        }
        
        .content {
            padding: 40px;
        }
        
        .service-card {
            background: #f7fafc;
            border-radius: 15px;
            margin-bottom: 40px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .service-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 30px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .service-header h2 {
            font-size: 1.8em;
        }
        
        .service-badge {
            background: rgba(255,255,255,0.2);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
        }
        
        .service-body {
            padding: 30px;
            display: none;
        }
        
        .service-body.active {
            display: block;
        }
        
        .section {
            margin-bottom: 30px;
        }
        
        .section h3 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 1.5em;
            border-right: 4px solid #667eea;
            padding-right: 15px;
        }
        
        .section h4 {
            color: #764ba2;
            margin: 15px 0 10px 0;
            font-size: 1.2em;
        }
        
        .code-block {
            background: #2d3748;
            color: #68d391;
            padding: 15px;
            border-radius: 8px;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
            margin: 15px 0;
        }
        
        .note {
            background: #fef5e7;
            border-right: 4px solid #f39c12;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        
        .tip {
            background: #e8f5e9;
            border-right: 4px solid #4caf50;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        
        .warning {
            background: #ffebee;
            border-right: 4px solid #f44336;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        
        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin: 20px 0;
        }
        
        .info-box {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
        }
        
        .info-box strong {
            color: #667eea;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        th, td {
            border: 1px solid #e2e8f0;
            padding: 12px;
            text-align: right;
        }
        
        th {
            background: #667eea;
            color: white;
        }
        
        .architecture-diagram {
            background: #f1f5f9;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin: 20px 0;
            font-family: monospace;
            white-space: pre;
            overflow-x: auto;
        }
        
        @media (max-width: 768px) {
            .content {
                padding: 20px;
            }
            .grid-2 {
                grid-template-columns: 1fr;
            }
            .nav a {
                font-size: 12px;
                padding: 5px 10px;
            }
        }
        
        .footer {
            background: #2d3748;
            color: white;
            text-align: center;
            padding: 30px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.8em;
            margin-right: 10px;
        }
        
        .status-running {
            background: #4caf50;
            color: white;
        }
        
        .status-healthy {
            background: #2196f3;
            color: white;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🐳 راهنمای جامع سرویس‌های Docker</h1>
        <p>آموزش کامل معماری میکروسرویس‌ها - از نصب تا مدیریت در محیط DevOps</p>
        <p style="font-size: 0.9em; margin-top: 10px;">📅 تاریخ: ۲۷ می ۲۰۲۶ | 👨‍💻 تهیه شده برای تیم توسعه و عملیات</p>
    </div>
    
    <div class="nav">
        <a href="#gateway">🚪 API Gateway</a>
        <a href="#auth">🔐 احراز هویت</a>
        <a href="#workflow">⚙️ پردازش workflow</a>
        <a href="#storage">💾 ذخیره‌سازی</a>
        <a href="#monitoring">📊 مانیتورینگ</a>
        <a href="#cicd">🚀 CI/CD</a>
        <a href="#database">🗄️ پایگاه داده</a>
        <a href="#messaging">📨 پیام‌رسانی</a>
    </div>
    
    <div class="content">
        <!-- معرفی کلی -->
        <div class="service-card">
            <div class="service-header" onclick="toggleService('intro')">
                <h2>📖 معرفی کلی معماری</h2>
                <span class="service-badge">پیش‌نیاز</span>
            </div>
            <div id="intro" class="service-body">
                <div class="section">
                    <h3>🏗️ معماری کلی سرویس‌ها</h3>
                    <p>در این داکیومنت، ۳۸ سرویس Docker راه‌اندازی شده در شبکه <code>cloud</code> را بررسی می‌کنیم. این سرویس‌ها یک پلتفرم کامل DevOps و میکروسرویس را تشکیل می‌دهند.</p>
                    
                    <div class="architecture-diagram">
┌─────────────────────────────────────────────────────────────────┐
│                         👤 کاربر نهایی                          │
│                    http://192.168.137.50                        │
└─────────────────────────────┬───────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                    🌐 Nginx Reverse Proxy                       │
│                       (ورودی یکتا - پورت 80)                     │
└──────────┬──────────┬──────────┬──────────┬──────────┬─────────┘
           │          │          │          │          │
           ▼          ▼          ▼          ▼          ▼
    ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐
    │ Gitlab   │ │ Jira     │ │ Keycloak │ │ Grafana  │ │ Kong     │
    │ (Code)   │ │ (Project)│ │ (Auth)   │ │ (Monitor)│ │ (Gateway)│
    └──────────┘ └──────────┘ └──────────┘ └──────────┘ └──────────┘
           │          │          │          │          │
           └──────────┴──────────┴──────────┴──────────┘
                              │
                              ▼
              ┌───────────────────────────────┐
              │        شبکه ابری cloud         │
              │    (ارتباط داخلی سرویس‌ها)     │
              └───────────────────────────────┘
                    </div>
                    
                    <div class="tip">
                        <strong>💡 نکته کلیدی:</strong> همه سرویس‌ها در شبکه <code>cloud</code> قرار دارند و با نام کانتینر به هم متصل می‌شوند. این یعنی هر سرویس می‌تواند سرویس دیگر را با نام آن صدا بزند (مثلاً <code>http://gitlab:80</code>).
                    </div>
                </div>
            </div>
        </div>

        <!-- 1. Kong API Gateway -->
        <div class="service-card" id="gateway">
            <div class="service-header" onclick="toggleService('kong')">
                <h2>🚪 1. Kong - API Gateway</h2>
                <span class="service-badge">لایه ورودی</span>
            </div>
            <div id="kong" class="service-body">
                <div class="section">
                    <h3>🎯 وظیفه و نقش</h3>
                    <p><strong>Kong</strong> یک API Gateway قدرتمند است که به عنوان دروازه ورودی همه ترافیک به میکروسرویس‌ها عمل می‌کند. وظایف اصلی آن:</p>
                    <ul>
                        <li>🔀 <strong>مسیریابی (Routing):</strong> هدایت درخواست‌ها به سرویس‌های مناسب</li>
                        <li>🔐 <strong>احراز هویت (Authentication):</strong> بررسی توکن‌ها و مجوزها</li>
                        <li>📊 <strong>محدودیت نرخ (Rate Limiting):</strong> جلوگیری از overload سرویس‌ها</li>
                        <li>📝 <strong>ثبت لاگ (Logging):</strong> ثبت همه درخواست‌ها و پاسخ‌ها</li>
                        <li>🔁 <strong>Load Balancing:</strong> توزیع ترافیک بین چندین نمونه از یک سرویس</li>
                    </ul>
                    
                    <div class="tip">
                        <strong>💡 مثال عملی:</strong> برنامه نویس فرانت‌اند به جای اینکه بداند سرویس کاربران روی چه آدرسی است، فقط به Kong درخواست می‌دهد: <code>POST /api/users</code> و Kong آن را به سرویس مناسب هدایت می‌کند.
                    </div>
                </div>

                <div class="section">
                    <h3>🔧 نحوه راه‌اندازی</h3>
                    <div class="code-block">
docker run -d \
  --name kong \
  --network cloud \
  -p 8000:8000 \
  -p 8001:8001 \
  -e KONG_DATABASE=postgres \
  -e KONG_PG_HOST=kong-db \
  -e KONG_PG_USER=kong \
  -e KONG_PG_PASSWORD=kong \
  kong:latest
                    </div>
                    <div class="grid-2">
                        <div class="info-box">
                            <strong>📌 پورت‌ها:</strong><br>
                            • 8000: HTTP API (ورودی اصلی)<br>
                            • 8001: Admin API (مدیریت)<br>
                            • 8443: HTTPS API<br>
                            • 8444: Admin HTTPS
                        </div>
                        <div class="info-box">
                            <strong>🗄️ دیتابیس:</strong><br>
                            • PostgreSQL (kong-db)<br>
                            • ذخیره کانفیگ‌ها و مسیرها
                        </div>
                    </div>
                </div>

                <div class="section">
                    <h3>🔗 ارتباط با سایر سرویس‌ها</h3>
                    <div class="architecture-diagram">
     کاربر → Kong → [نقشه مسیریابی] → سرویس مقصد
                         │
                         ├─→ Gitlab (مسیر /code)
                         ├─→ Jira (مسیر /project)
                         ├─→ Keycloak (مسیر /auth)
                         └─→ Camunda (مسیر /workflow)
                    </div>
                    
                    <h4>ایجاد مسیر (Route) در Kong:</h4>
                    <div class="code-block">
# ایجاد سرویس در Kong
curl -X POST http://localhost:8001/services \
  --data name=gitlab-service \
  --data url=http://gitlab:80

# ایجاد مسیر برای سرویس
curl -X POST http://localhost:8001/services/gitlab-service/routes \
  --data paths[]=/code

# نصب پلاگین احراز هویت
curl -X POST http://localhost:8001/services/gitlab-service/plugins \
  --data name=key-auth
                    </div>
                </div>

                <div class="section">
                    <h3>👨‍💻 نقش برنامه نویس</h3>
                    <ul>
                        <li>ثبت سرویس جدید در Kong از طریق Admin API</li>
                        <li>تعریف Routeها و مسیرهای API</li>
                        <li>پیکربندی احراز هویت برای endpoints</li>
                        <li>تنظیم rate limiting برای جلوگیری از سوء استفاده</li>
                    </ul>
                    
                    <h3>🛠️ نقش DevOps</h3>
                    <ul>
                        <li>مدیریت سلامت و uptime Kong</li>
                        <li>پشتیبان‌گیری از دیتابیس کانفیگ‌ها</li>
                        <li>نظارت بر لاگ‌ها و عملکرد Gateway</li>
                        <li>بروزرسانی Kong و پلاگین‌ها</li>
                    </ul>
                </div>
                
                <div class="section">
                    <h3>📊 مثال واقعی از مسیریابی</h3>
                    <div class="code-block">
# برنامه نویس یک سرویس جدید برای پرداخت ایجاد می‌کند
curl -X POST http://localhost:8001/services \
  --data name=payment-service \
  --data url=http://payment:8080

# مسیر را تعریف می‌کند
curl -X POST http://localhost:8001/services/payment-service/routes \
  --data paths[]=/api/payments \
  --data methods[]=POST \
  --data hosts[]=api.myapp.com

# حالا هر درخواست POST به /api/payments به payment-service می‌رود
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Keycloak -->
        <div class="service-card" id="auth">
            <div class="service-header" onclick="toggleService('keycloak')">
                <h2>🔐 2. Keycloak - Identity & Access Management</h2>
                <span class="service-badge">امنیت</span>
            </div>
            <div id="keycloak" class="service-body">
                <div class="section">
                    <h3>🎯 وظیفه و نقش</h3>
                    <p><strong>Keycloak</strong> یک سرویس جامع مدیریت هویت و دسترسی (IAM) است. وظایف اصلی:</p>
                    <ul>
                        <li>👤 <strong>Single Sign-On (SSO):</strong> ورود یکباره به همه سرویس‌ها</li>
                        <li>🔑 <strong>مدیریت کاربران:</strong> ایجاد، حذف و مدیریت کاربران</li>
                        <li>🎫 <strong>صدور توکن JWT:</strong> تولید توکن امن برای احراز هویت</li>
                        <li>👥 <strong>Roles & Permissions:</strong> تعریف نقش‌ها و مجوزها</li>
                        <li>🔗 <strong>Social Login:</strong> ورود با Google, GitHub و ...</li>
                    </ul>
                    
                    <div class="tip">
                        <strong>💡 مثال عملی:</strong> برنامه نویس می‌خواهد فقط کاربرانی با نقش "admin" به پنل مدیریت دسترسی داشته باشند. Keycloak این مجوز را در توکن JWT قرار می‌دهد و سرویس‌ها می‌توانند آن را بررسی کنند.
                    </div>
                </div>

                <div class="section">
                    <h3>🔧 نحوه راه‌اندازی</h3>
                    <div class="code-block">
docker run -d \
  --name keycloak \
  --network cloud \
  -p 8089:8080 \
  -e KEYCLOAK_ADMIN=admin \
  -e KEYCLOAK_ADMIN_PASSWORD=admin123 \
  -e KC_DB=postgres \
  -e KC_DB_URL=jdbc:postgresql://postgres-users:5432/keycloak \
  quay.io/keycloak/keycloak:26.3.2 \
  start-dev
                    </div>
                    <div class="grid-2">
                        <div class="info-box">
                            <strong>📌 پورت‌ها:</strong><br>
                            • 8089: پورت اصلی Keycloak<br>
                            • 8443: HTTPS (اختیاری)
                        </div>
                        <div class="info-box">
                            <strong>🗄️ دیتابیس:</strong><br>
                            • PostgreSQL (postgres-users)<br>
                            • ذخیره کاربران، نقش‌ها و sessions
                        </div>
                    </div>
                </div>

                <div class="section">
                    <h3>🔗 ارتباط با سایر سرویس‌ها</h3>
                    <div class="architecture-diagram">
    کاربر → Keycloak → [ورود/احراز هویت] → توکن JWT
                              │
                              ▼
    سرویس‌ها ← Kong → [بررسی توکن] → دسترسی مجاز/غیرمجاز
                    </div>
                    
                    <h4>پیکربندی Kong برای استفاده از Keycloak:</h4>
                    <div class="code-block">
# نصب پلاگین OpenID Connect در Kong
curl -X POST http://localhost:8001/services/gitlab-service/plugins \
  --data name=openid-connect \
  --data config.issuer=http://keycloak:8089/realms/myrealm \
  --data config.client_id=kong \
  --data config.client_secret=secret
                    </div>
                </div>

                <div class="section">
                    <h3>👨‍💻 نقش برنامه نویس</h3>
                    <ul>
                        <li>ایجاد Realm و Client برای هر سرویس</li>
                        <li>تعریف نقش‌ها (Roles) و مجوزهای دسترسی</li>
                        <li>یکپارچه‌سازی با سرویس‌ها (OAuth2/OIDC)</li>
                        <li>مدیریت کاربران و لاگین</li>
                    </ul>
                    
                    <h3>🛠️ نقش DevOps</h3>
                    <ul>
                        <li>پشتیبان‌گیری از دیتابیس Keycloak</li>
                        <li>مدیریت SSL/TLS برای ارتباط امن</li>
                        <li>نظارت بر سلامت سرویس و احراز هویت‌ها</li>
                        <li>مدیریت secret‌ها و client credentials</li>
                    </ul>
                </div>
                
                <div class="section">
                    <h3>📊 مثال: یکپارچه‌سازی با برنامه Next.js</h3>
                    <div class="code-block">
// در برنامه React/Next.js
import { useKeycloak } from '@react-keycloak/web';

function App() {
  const { keycloak, initialized } = useKeycloak();
  
  if (!initialized) return <div>Loading...</div>;
  
  if (!keycloak.authenticated) {
    return <button onClick={() => keycloak.login()}>Login</button>;
  }
  
  const token = keycloak.token; // توکن JWT برای API calls
  
  return (
    <div>
      <p>Welcome {keycloak.tokenParsed?.preferred_username}</p>
      <button onClick={() => keycloak.logout()}>Logout</button>
    </div>
  );
}
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Konga -->
        <div class="service-card">
            <div class="service-header" onclick="toggleService('konga')">
                <h2>📊 3. Konga - مدیریت بصری Kong</h2>
                <span class="service-badge">UI ابزار</span>
            </div>
            <div id="konga" class="service-body">
                <div class="section">
                    <h3>🎯 وظیفه و نقش</h3>
                    <p><strong>Konga</strong> یک رابط کاربری گرافیکی برای مدیریت API Gateway Kong است. بدون نیاز به خط فرمان، می‌توانید:</p>
                    <ul>
                        <li>🎛️ مدیریت سرویس‌ها و Routeها</li>
                        <li>🔌 نصب و پیکربندی پلاگین‌ها</li>
                        <li>📊 مشاهده وضعیت و سلامت سرویس‌ها</li>
                        <li>📝 بررسی لاگ‌ها و خطاها</li>
                    </ul>
                </div>

                <div class="section">
                    <h3>🔧 نحوه راه‌اندازی</h3>
                    <div class="code-block">
docker run -d \
  --name konga \
  --network cloud \
  -p 1337:1337 \
  pantsel/konga:latest
                    </div>
                    <div class="tip">
                        <strong>💡 دسترسی:</strong> بعد از راه‌اندازی، آدرس <code>http://192.168.137.50:1337</code> را باز کنید. ابتدا باید یک کاربر ایجاد کنید و سپس Kong Admin API را با آدرس <code>http://kong:8001</code> متصل کنید.
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. GitLab -->
        <div class="service-card">
            <div class="service-header" onclick="toggleService('gitlab')">
                <h2>📝 4. GitLab - Version Control & CI/CD</h2>
                <span class="service-badge">توسعه</span>
            </div>
            <div id="gitlab" class="service-body">
                <div class="section">
                    <h3>🎯 وظیفه و نقش</h3>
                    <p><strong>GitLab</strong> یک پلتفرم کامل DevOps است که شامل:</p>
                    <ul>
                        <li>📦 <strong>مدیریت کد:</strong> مخازن Git با قابلیت MR/PR</li>
                        <li>🚀 <strong>CI/CD Pipeline:</strong> خودکارسازی build, test, deploy</li>
                        <li>📋 <strong>مدیریت پروژه:</strong> Issue boards, milestones, wikis</li>
                        <li>🔐 <strong>مدیریت Package:</strong> Container registry, package registry</li>
                        <li>📊 <strong>مدیریت Vulnerability:</strong> اسکن امنیتی کد</li>
                    </ul>
                </div>

                <div class="section">
                    <h3>🔧 نحوه راه‌اندازی</h3>
                    <div class="code-block">
docker run -d \
  --name gitlab \
  --network cloud \
  -p 8890:80 \
  -p 2222:22 \
  -e GITLAB_OMNIBUS_CONFIG="external_url 'http://192.168.137.50'" \
  -v gitlab-config:/etc/gitlab \
  -v gitlab-logs:/var/log/gitlab \
  -v gitlab-data:/var/opt/gitlab \
  gitlab/gitlab-ce:latest
                    </div>
                    <div class="grid-2">
                        <div class="info-box">
                            <strong>📌 پورت‌ها:</strong><br>
                            • 8890: رابط کاربری HTTP<br>
                            • 2222: SSH برای git clone/push
                        </div>
                        <div class="info-box">
                            <strong>📁 ولوم‌ها:</strong><br>
                            • gitlab-config: تنظیمات<br>
                            • gitlab-data: مخازن و دیتا<br>
                            • gitlab-logs: لاگ‌ها
                        </div>
                    </div>
                </div>

                <div class="section">
                    <h3>🔗 ارتباط با سایر سرویس‌ها</h3>
                    <div class="architecture-diagram">
    Developer → git push → GitLab → [CI Pipeline]
                         │           │
                         ▼           ▼
                    Jira ← Webhook   Docker Registry
                    (Issue Update)   (Container Image)
                    </div>
                    
                    <h4>تنظیم Webhook به Kong برای ثبت API:</h4>
                    <div class="code-block">
# در تنظیمات GitLab: Settings → Webhooks
URL: http://kong:8001/services/gitlab-service/routes
Trigger: Push events, Merge request events
                    </div>
                </div>

                <div class="section">
                    <h3>👨‍💻 نقش برنامه نویس</h3>
                    <ul>
                        <li>ایجاد مخازن و مدیریت branches</li>
                        <li>نوشتن فایل <code>.gitlab-ci.yml</code> برای CI/CD</li>
                        <li>Review کردن Merge Requests</li>
                        <li>مدیریت Issues و Projects</li>
                    </ul>
                    
                    <h3>🛠️ نقش DevOps</h3>
                    <ul>
                        <li>مدیریت دسترسی‌ها و کاربران</li>
                        <li>پیکربندی CI/CD runners</li>
                        <li>پشتیبان‌گیری از دیتا و مخازن</li>
                        <li>مدیریت Container Registry</li>
                    </ul>
                </div>

                <div class="section">
                    <h3>📊 مثال فایل .gitlab-ci.yml</h3>
                    <div class="code-block">
stages:
  - build
  - test
  - deploy

variables:
  DOCKER_IMAGE: registry.gitlab.com/myproject/app

build:
  stage: build
  script:
    - docker build -t $DOCKER_IMAGE:latest .
    - docker push $DOCKER_IMAGE:latest

test:
  stage: test
  script:
    - npm test
    - npm run lint

deploy:
  stage: deploy
  script:
    - kubectl set image deployment/app app=$DOCKER_IMAGE:latest
  only:
    - main
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. Jira -->
        <div class="service-card">
            <div class="service-header" onclick="toggleService('jira')">
                <h2>📋 5. Jira - Project Management</h2>
                <span class="service-badge">مدیریت</span>
            </div>
            <div id="jira" class="service-body">
                <div class="section">
                    <h3>🎯 وظیفه و نقش</h3>
                    <p><strong>Jira</strong> ابزار مدیریت پروژه و پیگیری تسک‌ها (Issue Tracking) است:</p>
                    <ul>
                        <li>📌 <strong>مدیریت تسک‌ها:</strong> ایجاد، تخصیص و پیگیری Issues</li>
                        <li>📊 <strong>اسپرینت‌ها:</strong> مدیریت متدولوژی Agile و Scrum</li>
                        <li>📈 <strong>گزارشات:</strong> Burndown charts, Velocity, Cumulative flow</li>
                        <li>🔗 <strong>یکپارچگی:</strong> اتصال با GitLab, Bitbucket, GitHub</li>
                    </ul>
                </div>

                <div class="section">
                    <h3>🔧 نحوه راه‌اندازی</h3>
                    <div class="code-block">
docker run -d \
  --name jira \
  --network cloud \
  -p 8091:8080 \
  -v jira-data:/var/atlassian/application-data/jira \
  -e ATL_PROXY_NAME=192.168.137.50 \
  -e ATL_PROXY_PORT=80 \
  jira:10.5.1
                    </div>
                    <div class="tip">
                        <strong>💡 نکته:</strong> Jira نیاز به لایسنس دارد. می‌توانید نسخه رایگان برای تیم‌های کوچک (حداکثر ۱۰ کاربر) استفاده کنید.
                    </div>
                </div>

                <div class="section">
                    <h3>🔗 ارتباط با سایر سرویس‌ها</h3>
                    <div class="code-block">
# یکپارچه‌سازی GitLab با Jira
# در تنظیمات GitLab: Settings → Integrations → Jira
URL: http://jira:8080
Project key: MYPROJ
Username: jira-user
Password: ****
                    </div>
                    <div class="tip">
                        <strong>💡 مثال:</strong> وقتی برنامه نویس در GitLab کامیت می‌کند با متن <code>PROJ-123 fix bug</code>، Jira به‌طور خودکار Issue #123 را آپدیت می‌کند.
                    </div>
                </div>
            </div>
        </div>

        <!-- 6. Camunda -->
        <div class="service-card">
            <div class="service-header" onclick="toggleService('camunda')">
                <h2>⚙️ 6. Camunda - Workflow Engine</h2>
                <span class="service-badge">پردازش</span>
            </div>
            <div id="camunda" class="service-body">
                <div class="section">
                    <h3>🎯 وظیفه و نقش</h3>
                    <p><strong>Camunda</strong> یک موتور پردازش workflow و BPMN است:</p>
                    <ul>
                        <li>📊 <strong>مدل‌سازی فرآیند:</strong> طراحی فرآیندهای کسب و کار با BPMN</li>
                        <li>🔄 <strong>اجرای workflow:</strong> اجرای خودکار مراحل فرآیند</li>
                        <li>⚡ <strong>Zeebe:</strong> موتور میکروسرویس برای workflowهای سنگین</li>
                        <li>📝 <strong>Decision Automation:</strong> اجرای قوانین کسب و کار با DMN</li>
                    </ul>
                </div>

                <div class="section">
                    <h3>🔧 نحوه راه‌اندازی</h3>
                    <div class="code-block">
docker run -d \
  --name camunda \
  --network cloud \
  -p 8080:8080 \
  -p 26500:26500 \
  camunda/camunda:8.8.24
                    </div>
                    <div class="grid-2">
                        <div class="info-box">
                            <strong>📌 پورت‌ها:</strong><br>
                            • 8080: رابط کاربری Operate/Tasklist<br>
                            • 26500: gRPC برای Zeebe
                        </div>
                        <div class="info-box">
                            <strong>📁 دیتابیس:</strong><br>
                            • Elasticsearch: ذخیره history<br>
                            • RocksDB: داده‌های runtime
                        </div>
                    </div>
                </div>

                <div class="section">
                    <h3>🔗 ارتباط با سایر سرویس‌ها</h3>
                    <div class="code-block">
// اتصال به Camunda از برنامه Node.js
import { ZBClient } from 'zeebe-node';

const client = new ZBClient('camunda:26500');

// شروع یک workflow
await client.createWorkflowInstance({
  bpmnProcessId: 'order-process',
  variables: {
    orderId: 'ORD-123',
    amount: 1500
  }
});

// تعریف یک Job Worker
client.createWorker({
  taskType: 'process-payment',
  taskHandler: async (job) => {
    // پردازش پرداخت
    return job.complete({ status: 'paid' });
  }
});
                    </div>
                </div>

                <div class="section">
                    <h3>👨‍💻 نقش برنامه نویس</h3>
                    <ul>
                        <li>طراحی BPMN diagramها با Camunda Modeler</li>
                        <li>نوشتن Job Workers در زبان‌های مختلف (Java, Node.js, Go)</li>
                        <li>تعریف متغیرها و شرایط در workflow</li>
                    </ul>
                </div>

                <div class="section">
                    <h3>📊 مثال: فرآیند سفارش آنلاین</h3>
                    <div class="code-block">
BPMN Process:
┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│ دریافت سفارش │────▶│ تایید موجودی │────▶│ پردازش پرداخت│
└─────────────┘     └─────────────┘     └─────────────┘
                           │                    │
                           ▼                    ▼
                    ┌─────────────┐     ┌─────────────┐
                    │ تأیید انبار  │────▶│ ارسال کالا   │
                    └─────────────┘     └─────────────┘
                    </div>
                </div>
            </div>
        </div>

        <!-- 7. Elasticsearch -->
        <div class="service-card">
            <div class="service-header" onclick="toggleService('elasticsearch')">
                <h2>🔍 7. Elasticsearch - Search & Analytics</h2>
                <span class="service-badge">جستجو</span>
            </div>
            <div id="elasticsearch" class="service-body">
                <div class="section">
                    <h3>🎯 وظیفه و نقش</h3>
                    <p><strong>Elasticsearch</strong> یک موتور جستجوی توزیع‌شده و تحلیل داده است:</p>
                    <ul>
                        <li>🔍 <strong>جستجوی متن کامل:</strong> جستجوی سریع در لاگ‌ها و داکیومنت‌ها</li>
                        <li>📊 <strong>تحلیل داده:</strong> aggregation و analytics</li>
                        <li>📈 <strong>ذخیره‌سازی زمان‌محور:</strong> نگهداری لاگ‌ها و metricها</li>
                        <li>🌐 <strong>جغرافیایی:</strong> پشتیبانی از queryهای مکانی</li>
                    </ul>
                </div>

                <div class="section">
                    <h3>🔧 نحوه راه‌اندازی</h3>
                    <div class="code-block">
docker run -d \
  --name elasticsearch \
  --network cloud \
  -p 9200:9200 \
  -e "discovery.type=single-node" \
  -e "xpack.security.enabled=false" \
  docker.elastic.co/elasticsearch/elasticsearch:8.16.0
                    </div>
                    <div class="tip">
                        <strong>💡 مثال جستجو:</strong>
                        <code>GET /products/_search?q=name:"laptop" AND price:<1000</code>
                    </div>
                </div>
            </div>
        </div>

        <!-- 8. Grafana & Prometheus -->
        <div class="service-card">
            <div class="service-header" onclick="toggleService('monitoring')">
                <h2>📈 8. Grafana + Prometheus - Monitoring</h2>
                <span class="service-badge">مانیتورینگ</span>
            </div>
            <div id="monitoring" class="service-body">
                <div class="section">
                    <h3>🎯 وظیفه و نقش</h3>
                    <p><strong>Prometheus</strong> جمع‌آوری metrics و <strong>Grafana</strong> نمایش بصری آنها:</p>
                    <ul>
                        <li>📊 <strong>نظارت بر CPU/Memory:</strong> مصرف منابع سرویس‌ها</li>
                        <li>⏱️ <strong>Latency & Throughput:</strong> زمان پاسخ APIها</li>
                        <li>🚨 <strong>Alerting:</strong> هشدار در صورت مشکل</li>
                        <li>📈 <strong>Dashboard:</strong> داشبوردهای سفارشی</li>
                    </ul>
                </div>

                <div class="section">
                    <h3>🔧 نحوه راه‌اندازی</h3>
                    <div class="code-block">
# Prometheus
docker run -d \
  --name prometheus \
  --network cloud \
  -p 9090:9090 \
  -v prometheus.yml:/etc/prometheus/prometheus.yml \
  prom/prometheus:latest

# Grafana
docker run -d \
  --name grafana \
  --network cloud \
  -p 3000:3000 \
  grafana/grafana:latest
                    </div>
                    <div class="tip">
                        <strong>💡 دسترسی:</strong> Grafana: http://192.168.137.50:3000 (admin/admin123)
                    </div>
                </div>
            </div>
        </div>

        <!-- جدول خلاصه تمام سرویس‌ها -->
        <div class="service-card">
            <div class="service-header" onclick="toggleService('summary')">
                <h2>📊 جدول خلاصه همه سرویس‌ها</h2>
                <span class="service-badge">مرجع سریع</span>
            </div>
            <div id="summary" class="service-body">
                <table>
                    <thead>
                        <tr><th>سرویس</th><th>وظیفه</th><th>پورت</th><th>نقش برنامه نویس</th><th>نقش DevOps</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Kong</td><td>API Gateway</td><td>8000,8001</td><td>تعریف Routeها</td><td>مدیریت پلاگین‌ها</td></tr>
                        <tr><td>Konga</td><td>UI مدیریت Kong</td><td>1337</td><td>مدیریت گرافیکی</td><td>نظارت</td></tr>
                        <tr><td>Keycloak</td><td>احراز هویت</td><td>8089</td><td>ایجاد Realm/Client</td><td>مدیریت کاربران</td></tr>
                        <tr><td>GitLab</td><td>کد و CI/CD</td><td>8890,2222</td><td>Push, MR, Pipeline</td><td>مدیریت Runner</td></tr>
                        <tr><td>Jira</td><td>مدیریت پروژه</td><td>8091</td><td>مدیریت Issues</td><td>یکپارچه‌سازی</td></tr>
                        <tr><td>Camunda</td><td>Workflow Engine</td><td>8080,26500</td><td>طراحی BPMN</td><td>مدیریت موتور</td></tr>
                        <tr><td>Elasticsearch</td><td>جستجو</td><td>9200</td><td>Query داده</td><td>مدیریت ایندکس‌ها</td></tr>
                        <tr><td>Grafana</td><td>Dashboard</td><td>3000</td><td>ایجاد نمودار</td><td>مدیریت Data Source</td></tr>
                        <tr><td>Prometheus</td><td>Metrics</td><td>9090</td><td>تعریف Metrics</td><td>مدیریت Alert</td></tr>
                        <tr><td>Redis</td><td>Cache</td><td>6379</td><td>استفاده از کش</td><td>مدیریت حافظه</td></tr>
                        <tr><td>RabbitMQ</td><td>Message Broker</td><td>5672,15672</td><td>تعریف Queue</td><td>مدیریت Exchange</td></tr>
                        <tr><td>Kafka</td><td>Streaming</td><td>9092</td><td>Producer/Consumer</td><td>مدیریت Topic</td></tr>
                        <tr><td>PostgreSQL</td><td>Database</td><td>5432-5435</td><td>طراحی Schema</td><td>مدیریت backup</td></tr>
                        <tr><td>Nginx</td><td>Reverse Proxy</td><td>80</td><td>تنظیم مسیرها</td><td>مدیریت SSL</td></tr>
                        <tr><td>Sonarqube</td><td>کد Quality</td><td>9000</td><td>رفع باگ‌ها</td><td>مدیریت Quality Gate</td></tr>
                        <tr><td>Nexus</td><td>Repository</td><td>8085</td><td>ذخیره artifact</td><td>مدیریت Repository</td></tr>
                        <tr><td>Vault</td><td>Secret Management</td><td>8200</td><td>دریافت secret</td><td>مدیریت policy</td></tr>
                        <tr><td>MinIO</td><td>Object Storage</td><td>32771</td><td>ذخیره فایل</td><td>مدیریت Bucket</td></tr>
                        <tr><td>SpiceDB</td><td>Authorization</td><td>8443,50051</td><td>تعریف Permission</td><td>مدیریت Schema</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- آموزش ارتباط بین سرویس‌ها -->
        <div class="service-card">
            <div class="service-header" onclick="toggleService('communication')">
                <h2>🔗 نحوه ارتباط سرویس‌ها با هم</h2>
                <span class="service-badge">معماری</span>
            </div>
            <div id="communication" class="service-body">
                <div class="section">
                    <h3>📡 انواع ارتباط در معماری میکروسرویس</h3>
                    <div class="architecture-diagram">
┌─────────────────────────────────────────────────────────────────────┐
│                         شبکه مشترک cloud                             │
│  همه سرویس‌ها با نام کانتینر به هم متصل می‌شوند                      │
│  مثال: http://gitlab:80 , http://keycloak:8089 , http://kong:8000  │
└─────────────────────────────────────────────────────────────────────┘
                              │
        ┌─────────────────────┼─────────────────────┐
        │                     │                     │
        ▼                     ▼                     ▼
┌───────────────┐    ┌───────────────┐    ┌───────────────┐
│ همزمان (Sync) │    │ ناهمزمان(Async)│    │ Event-Driven  │
│ HTTP/gRPC     │    │ RabbitMQ/Kafka│    │ Webhook       │
│ Kong, GitLab  │    │ Queue, Stream │    │ GitLab→Jira   │
└───────────────┘    └───────────────┘    └───────────────┘
                    </div>
                </div>
                
                <div class="section">
                    <h3>🔹 1. ارتباط همزمان (HTTP/gRPC)</h3>
                    <div class="code-block">
// برنامه نویس از کد خود به Kong درخواست می‌دهد
fetch('http://kong:8000/api/users', {
  headers: { 'Authorization': `Bearer ${token}` }
});

// Kong درخواست را به سرویس مناسب هدایت می‌کند
// مسیر /api/users → user-service:8080
                    </div>
                </div>

                <div class="section">
                    <h3>🔹 2. ارتباط ناهمزمان (Message Queue)</h3>
                    <div class="code-block">
// Producer - ارسال پیام به RabbitMQ
channel.sendToQueue('order-queue', Buffer.from(JSON.stringify(order)));

// Consumer - دریافت پیام
channel.consume('order-queue', (msg) => {
  const order = JSON.parse(msg.content.toString());
  // پردازش سفارش
});
                    </div>
                </div>

                <div class="section">
                    <h3>🔹 3. Event-Driven (Webhook)</h3>
                    <div class="code-block">
# GitLab → Jira: وقتی issue بسته می‌شود، Jira آپدیت شود
# تنظیم در GitLab: Settings → Webhooks
URL: http://jira:8080/rest/webhook/1.0/...
Trigger: Issue events
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="footer">
        <p>📚 این داکیومنت به صورت زنده و پویا تهیه شده است</p>
        <p>🐳 Docker Environment | 🌐 Network: cloud | 📅 آخرین بروزرسانی: می 2026</p>
        <p style="margin-top: 10px; font-size: 0.8em;">تهیه شده برای تیم توسعه - هر سوالی دارید، در داکیومنت مربوطه جستجو کنید</p>
    </div>
</div>

<script>
function toggleService(id) {
    const element = document.getElementById(id);
    if (element.classList.contains('active')) {
        element.classList.remove('active');
    } else {
        // بستن همه
        document.querySelectorAll('.service-body').forEach(el => {
            el.classList.remove('active');
        });
        element.classList.add('active');
    }
}

// باز کردن اولین سرویس به صورت پیش‌فرض
document.addEventListener('DOMContentLoaded', () => {
    // باز کردن بخش معرفی
    toggleService('intro');
});
</script>
</body>
</html>
