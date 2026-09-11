<?php
require_once 'config.php';

$nginx_commands = [
    ['Nginx', 'nginx -t', 'تست صحت تنظیمات Nginx', 'test,config,validate', 'nginx -t', 'nginx -T'],
    
    ['Nginx', 'nginx -s reload', 'بارگذاری مجدد تنظیمات بدون قطعی', 'reload,restart,config', 'nginx -s reload', 'systemctl reload nginx'],
    
    ['Nginx', 'nginx -s stop', 'توقف سریع Nginx', 'stop,kill,emergency', 'nginx -s stop', 'nginx -s quit'],
    
    ['Nginx', 'nginx -s quit', 'توقف ملایم Nginx', 'stop,graceful,shutdown', 'nginx -s quit', 'kill -QUIT'],
    
    ['Nginx', 'systemctl start nginx', 'شروع سرویس Nginx', 'start,service,launch', 'systemctl start nginx', 'service nginx start'],
    
    ['Nginx', 'systemctl status nginx', 'وضعیت سرویس Nginx', 'status,check,running', 'systemctl status nginx', 'ps aux | grep nginx'],
    
    ['Nginx', 'journalctl -u nginx -f', 'مشاهده لاگ‌های Nginx', 'logs,debug,error', 'journalctl -u nginx -f', 'tail -f /var/log/nginx/error.log'],
    
    ['Nginx', 'vim /etc/nginx/nginx.conf', 'ویرایش فایل اصلی کانفیگ', 'edit,config,main', 'vim /etc/nginx/nginx.conf', 'nano /etc/nginx/nginx.conf'],
    
    ['Nginx', 'ls /etc/nginx/sites-available/', 'لیست سایت‌های فعال', 'sites,config,available', 'ls /etc/nginx/sites-available/', 'ls /etc/nginx/sites-enabled/'],
    
    ['Nginx', 'ln -s /etc/nginx/sites-available/mysite /etc/nginx/sites-enabled/', 'فعال کردن سایت جدید', 'enable,site,symlink', 'ln -s /etc/nginx/sites-available/mysite /etc/nginx/sites-enabled/', 'unlink /etc/nginx/sites-enabled/mysite'],
];

$stmt = $pdo->prepare("INSERT IGNORE INTO categories (category) VALUES ('Nginx')");
$stmt->execute();

$success = 0;
foreach($nginx_commands as $cmd) {
    $check = $pdo->prepare("SELECT COUNT(*) FROM commands WHERE command = ? AND category = 'Nginx'");
    $check->execute([$cmd[1]]);
    
    if($check->fetchColumn() == 0) {
        $stmt = $pdo->prepare("INSERT INTO commands (category, command, description, keywords, example, similar_commands) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute($cmd);
        $success++;
    }
}

echo "✅ $success دستور Nginx با موفقیت اضافه شد!";
?>