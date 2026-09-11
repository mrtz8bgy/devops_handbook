<?php
require_once 'config.php';

// لیست کامل دستورات پرکاربرد DevOps - بخش سرویس‌های Docker (PostgreSQL, GitLab, Jira, Nexus)
$commands_list = [
    // ==================== دستورات DOCKER - مدیریت عمومی ====================
    ['Docker', 'docker ps', 'مشاهده کانتینرهای در حال اجرا', 'container,list,running,status', 'docker ps -a (همه کانتینرها)', 'docker ps -a, docker stats'],
    ['Docker', 'docker logs', 'مشاهده لاگ کانتینر', 'logs,debug,error,tail', 'docker logs --tail 50 gitlab', 'docker logs -f, docker events'],
    ['Docker', 'docker exec', 'ورود به کانتینر در حال اجرا', 'exec,enter,bash,shell', 'docker exec -it gitlab bash', 'docker attach, docker run -it'],
    ['Docker', 'docker restart', 'ریستارت کانتینر', 'restart,reboot,reset', 'docker restart gitlab', 'docker stop/start, docker-compose restart'],
    ['Docker', 'docker-compose up', 'اجرای سرویس‌های docker-compose', 'compose,start,deploy,run', 'docker-compose up -d (حالت detached)', 'docker-compose down, docker-compose start'],
    ['Docker', 'docker-compose down', 'توقف و حذف سرویس‌های docker-compose', 'stop,remove,clean,down', 'docker-compose down -v (حذف volumeها)', 'docker-compose stop, docker rm'],
    
    // ==================== دستورات POSTGRESQL ====================
    ['PostgreSQL', 'psql -U admin -d devops_db', 'ورود به PostgreSQL با کاربر admin', 'postgres,login,connect,database', 'docker exec -it postgres psql -U admin -d devops_db', 'psql -U postgres, pg_isready'],
    ['PostgreSQL', 'CREATE DATABASE', 'ساخت دیتابیس جدید', 'create,database,new,db', 'CREATE DATABASE jiradb OWNER jiradbuser;', 'createdb, CREATE SCHEMA'],
    ['PostgreSQL', 'CREATE USER', 'ساخت کاربر جدید در PostgreSQL', 'user,create,role,account', 'CREATE USER mahpooya WITH PASSWORD \'m123456\';', 'CREATE ROLE, ALTER USER'],
    ['PostgreSQL', 'GRANT ALL PRIVILEGES', 'اعطای همه دسترسی‌ها به کاربر روی دیتابیس', 'grant,privileges,access,permission', 'GRANT ALL PRIVILEGES ON DATABASE jiradb TO jiradbuser;', 'GRANT SELECT, GRANT INSERT'],
    ['PostgreSQL', '\l', 'لیست همه دیتابیس‌ها', 'list,database,show,l', '\\l (در محیط psql)', 'SELECT datname FROM pg_database;'],
    ['PostgreSQL', '\dt', 'لیست جدول‌های دیتابیس جاری', 'tables,list,show', '\\dt (در محیط psql)', 'SELECT tablename FROM pg_tables;'],
    ['PostgreSQL', '\du', 'لیست کاربران PostgreSQL', 'users,list,roles', '\\du (در محیط psql)', 'SELECT usename FROM pg_user;'],
    ['PostgreSQL', 'pg_dump', 'بکاپ‌گیری از دیتابیس', 'backup,dump,export,sql', 'docker exec postgres pg_dump -U admin -d devops_db > backup.sql', 'pg_restore, COPY'],
    
    // ==================== دستورات GITLAB ====================
    ['GitLab', 'gitlab-rails console', 'ورود به کنسول Rails گیت‌لب', 'console,rails,admin,manage', 'docker exec -it gitlab gitlab-rails console', 'gitlab-rails runner'],
    ['GitLab', 'gitlab-ctl reconfigure', 'بازپیکربندی گیت‌لب بعد از تغییر تنظیمات', 'reconfigure,apply,restart,config', 'docker exec -it gitlab gitlab-ctl reconfigure', 'gitlab-ctl restart'],
    ['GitLab', 'gitlab-ctl status', 'وضعیت سرویس‌های داخلی گیت‌لب', 'status,health,services,check', 'docker exec -it gitlab gitlab-ctl status', 'gitlab-ctl tail'],
    ['GitLab', 'initial_root_password', 'دریافت رمز عبور root اولیه', 'password,root,initial,reset', 'docker exec gitlab cat /etc/gitlab/initial_root_password', 'gitlab-rails runner "User.find(1).password"'],
    ['GitLab', 'تغییر رمز root در کنسول', 'تغییر رمز کاربر root گیت‌لب', 'password,change,reset,root', "docker exec -it gitlab gitlab-rails runner 'u=User.first; u.password=\"Admin@123!\"; u.save!'", 'gitlab-rails console'],
    ['GitLab', 'git clone SSH', 'کلون مخزن با SSH (پورت 2222)', 'clone,ssh,repository,download', 'git clone ssh://git@192.168.137.50:2222/root/project.git', 'git clone --mirror'],
    ['GitLab', 'git push SSH', 'آپلود کد به مخزن با SSH', 'push,upload,ssh,commit', 'git push -u origin main', 'git push --force'],
    
    // ==================== دستورات JIRA ====================
    ['Jira', 'JIRA_HOME', 'تنظیم دایرکتوری خانه جیرا', 'home,config,setup,path', '-e JIRA_HOME=/var/jira-home (در docker run)', 'ATL_JIRA_HOME'],
    ['Jira', 'Setup دیتابیس جیرا', 'اتصال جیرا به PostgreSQL', 'database,setup,postgres,config', 'Host: postgres, Port: 5432, DB: jiradb, User: jiradbuser, Pass: Admin@123!', 'ATL_JDBC_URL'],
    ['Jira', 'لاگ جیرا', 'مشاهده لاگ خطاهای جیرا', 'logs,error,debug,troubleshoot', 'docker logs jira --tail 50', 'docker logs -f jira'],
    ['Jira', 'ریستارت جیرا', 'راه‌اندازی مجدد کانتینر جیرا', 'restart,reboot,reload', 'docker restart jira', 'docker-compose restart jira'],
    
    // ==================== دستورات NEXUS ====================
    ['Nexus', 'admin.password', 'دریافت رمز عبور اولیه Nexus', 'password,admin,initial,reset', 'docker exec nexus cat /nexus-data/admin.password', 'cat /opt/nexus-data/admin.password'],
    ['Nexus', 'تغییر رمز Nexus با API', 'تغییر رمز کاربر admin از طریق API', 'api,password,change,reset', "curl -X PUT 'http://localhost:8085/service/rest/v1/security/users/admin/change-password' -u 'admin:oldpass' -H 'Content-Type: text/plain' -d 'Admin@123!'", 'UI change password'],
    ['Nexus', 'Nexus API وضعیت', 'بررسی وضعیت Nexus از طریق API', 'status,health,api,check', 'curl -u "admin:Admin@123!" "http://192.168.137.50:8085/service/rest/v1/status"', 'curl -I http://localhost:8085'],
    ['Nexus', 'nexus-data volume', 'مدیریت دیتای Nexus', 'volume,data,storage,backup', 'docker volume rm ops_nexus-data (⚠️ حذف همه دیتا)', 'docker volume prune'],
    
    // ==================== دستورات NETWORK (برای رفع قطعی اینترنت) ====================
    ['Network', 'ip route show', 'نمایش جدول مسیریابی', 'route,routing,gateway', 'ip route show default (نمایش گیت‌وی)', 'route -n, netstat -rn'],
    ['Network', 'افزودن گیت‌وی پیش‌فرض', 'تنظیم دستی گیت‌وی اینترنت', 'gateway,add,route,default', 'sudo ip route add default via 192.168.137.1 dev ens224', 'nmcli con mod'],
    ['Network', 'nmcli connection modify', 'تنظیم دائمی گیت‌وی با NetworkManager', 'gateway,static,persistent,nmcli', 'sudo nmcli connection modify "Profile 1" ipv4.gateway 192.168.137.1', 'nmtui'],
    ['Network', 'ping 8.8.8.8', 'تست اتصال اینترنت', 'internet,test,connectivity,ping', 'ping -c 4 8.8.8.8', 'traceroute, curl'],
    
    // ==================== دستورات SSL و HTTPS ====================
    ['SSL', 'openssl req', 'ساخت گواهی Self-Signed SSL', 'ssl,certificate,https,self-signed', 'openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout server.key -out server.crt', 'openssl genrsa'],
    ['SSL', 'nginx.conf location /jira/', 'تنظیمات پروکسی معکوس جیرا در Nginx', 'proxy,reverse,jira,nginx', "rewrite ^/jira(/.*)$ $1 break; proxy_pass http://jira:8080;", 'proxy_set_header'],
    
    // ==================== دستورات NGINX PROXY ====================
    ['Nginx', 'nginx.conf upstream', 'تعریف سرویس‌های پشتیبان در Nginx', 'upstream,backend,proxy', 'upstream gitlab { server gitlab:80; }', 'proxy_pass'],
    ['Nginx', 'nginx-proxy ریستارت', 'ریستارت کانتینر reverse proxy', 'restart,reload,nginx', 'docker restart nginx-proxy', 'docker exec nginx-proxy nginx -s reload'],
    ['Nginx', 'بررسی لاگ Nginx', 'مشاهده خطاهای Nginx', 'logs,error,debug,nginx', 'docker logs nginx-proxy --tail 30', 'docker logs -f nginx-proxy'],
    
    // ==================== دستورات VOLUME و NETWORK (سرویس‌های جدید) ====================
    ['Docker', 'docker volume create', 'ساخت volume جدید برای داده‌های ماندگار', 'volume,storage,data,persist', 'docker volume create nexus-data', 'docker volume ls'],
    ['Docker', 'docker network connect', 'اتصال کانتینر به شبکه خاص', 'network,connect,isolated', 'docker network connect ops_isolated-net nginx-proxy', 'docker network disconnect'],
    ['Docker', 'docker network ls', 'لیست شبکه‌های Docker', 'network,list,show', 'docker network ls | grep isolated', 'docker network inspect'],
];

// اضافه کردن دسته‌بندی‌های جدید به جدول categories
$categories = ['Linux', 'Docker', 'Git', 'Kubernetes', 'Network', 'PostgreSQL', 'GitLab', 'Jira', 'Nexus', 'SSL', 'Nginx'];
foreach($categories as $cat) {
    try {
        $stmt = $pdo->prepare("INSERT IGNORE INTO categories (category) VALUES (?)");
        $stmt->execute([$cat]);
    } catch(PDOException $e) {
        // دسته‌بندی از قبل وجود دارد
    }
}

// اضافه کردن دستورات به دیتابیس
$success_count = 0;
$error_count = 0;

foreach($commands_list as $cmd) {
    try {
        $check = $pdo->prepare("SELECT COUNT(*) FROM commands WHERE command = ? AND category = ?");
        $check->execute([$cmd[1], $cmd[0]]);
        
        if($check->fetchColumn() == 0) {
            $stmt = $pdo->prepare("INSERT INTO commands (category, command, description, keywords, example, similar_commands) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute($cmd);
            $success_count++;
        } else {
            $error_count++;
        }
    } catch(PDOException $e) {
        $error_count++;
    }
}

// نمایش نتیجه نهایی
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>نتیجه وارد کردن دستورات | DevOps Commands</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 20px;
        }
        .result-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            max-width: 500px;
        }
        .success { color: #28a745; font-size: 48px; }
        .info { color: #17a2b8; font-size: 24px; margin: 10px 0; }
        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            transition: transform 0.3s;
        }
        .button:hover { transform: translateY(-3px); }
    </style>
</head>
<body>
    <div class="result-card">
        <div class="success">✅</div>
        <h2>عملیات با موفقیت انجام شد!</h2>
        <div class="info">📊 تعداد دستورات جدید اضافه شده: <strong><?php echo $success_count; ?></strong></div>
        <?php if($error_count > 0): ?>
            <div style="color: #ffc107;">⚠️ تعداد دستورات تکراری: <?php echo $error_count; ?></div>
        <?php endif; ?>
        <div style="font-size: 14px; color: #6c757d; margin-top: 15px;">
            دستورات مربوط به <strong>PostgreSQL, GitLab, Jira, Nexus, Nginx, SSL</strong> اضافه شدند.
        </div>
        <a href="index.php" class="button">🏠 رفتن به صفحه اصلی</a>
    </div>
</body>
</html>