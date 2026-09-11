# 📘 DevOps Handbook — مرجع فارسی دستورات DevOps

کتابخانه فارسی و راست‌به‌چپ دستورات DevOps با **موتور جستجوی حرفه‌ای**،
**چت‌بات هوشمند**، **سیستم لاگین** و **پنل مدیریت امن** — با قالب لوکس سرمه‌ای/طلایی.

## ✨ امکانات (نسخه ۳)

| بخش | امکانات |
|---|---|
| 🔍 جستجو | FULLTEXT بولی + تطابق دقیق/پیشوندی + فال‌بک OR + رتبه‌بندی + هایلایت + فیلتر دسته + پیشنهاد زنده (AJAX) + ثبت لاگ جستجو |
| 🤖 چت‌بات | نرمال‌سازی فارسی، مترادف فنی (بکاپ، لاگ، کانتینر…)، تشخیص دسته («دستورات داکر»)، پاسخ QA، ثبت سؤال ناشناخته، بازخورد 👍👎 |
| 🔐 احراز هویت | ثبت‌نام/ورود/خروج، هش امن رمز، CSRF، نقش `admin` و `user` |
| 🛡 پنل ادمین | داشبورد آماری، مدیریت کاربران/دستورات/دسته‌ها/QA/سؤالات ناشناخته — همه صفحات پشت `require_admin` |
| 🎨 قالب | RTL، سرمه‌ای/طلایی، ریسپانسیو، بدون وابستگی خارجی |

## 🚀 نصب

```bash
# ۱. ایمپورت دیتای پایه
mysql -u root -p devops_handbook < db/fulldb/*.sql   # یا فایل‌های sql موجود

# ۲. تنظیم اتصال (یکی از این دو)
export DB_HOST=localhost DB_NAME=devops_handbook DB_USER=devops DB_PASS=secret
# یا ویرایش مستقیم config.php

# ۳. اجرای نصب (ساخت جداول users/search_logs، ایندکس FULLTEXT، ادمین پیش‌فرض)
php -S localhost:8080
# باز کردن: http://localhost:8080/install.php
```

> 👤 ادمین پیش‌فرض: `admin` / `Admin123!` — بعد از ورود حتماً عوضش کن!

## 🗺 نقشه مسیرها

- `/index.php` — صفحه اصلی (آمار زنده، جستجوی زنده، دسته‌ها، محبوب‌ها)
- `/user/index_readonly.php` — مرور دستورات (`?cat=Docker&page=2`)
- `/user/search_readonly.php` — جستجو (`?q=…&cat=…`)
- `/user/command_detail_readonly.php?id=…` — جزئیات دستور
- `/user/chatbot.php` — چت‌بات
- `/api/{suggest,search,feedback}.php` — APIهای JSON
- `/login.php` `/register.php` `/logout.php` — احراز هویت
- `/admin/` — پنل مدیریت (نیازمند لاگین ادمین)

## 🛠 تکنولوژی

PHP 8 + PDO + MySQL/MariaDB (utf8mb4) — بدون فریم‌ورک، بدون CDN.
