<?php
require_once 'config.php';
require_once __DIR__ . '/../../includes/bootstrap.php';
require_admin($pdo); // v3: فقط ادمین

$postgresql_commands = [
    ['PostgreSQL', 'psql -U username -d dbname', 'اتصال به دیتابیس PostgreSQL', 'connect,login,access', 'psql -U postgres -d mydb', 'pgcli, pgadmin'],
    
    ['PostgreSQL', '\\l', 'لیست تمام دیتابیس‌ها', 'list,databases,show', '\\l', 'SELECT datname FROM pg_database;'],
    
    ['PostgreSQL', '\\c dbname', 'اتصال به دیتابیس مشخص', 'connect,switch,use', '\\c mydb', 'CONNECT TO'],
    
    ['PostgreSQL', '\\dt', 'لیست جدول‌های دیتابیس جاری', 'tables,list,schema', '\\dt', '\\d, \\d+'],
    
    ['PostgreSQL', '\\d tablename', 'نمایش ساختار جدول', 'describe,schema,columns', '\\d users', '\\d+, \\dt'],
    
    ['PostgreSQL', '\\du', 'لیست کاربران دیتابیس', 'users,roles,list', '\\du', 'SELECT * FROM pg_user;'],
    
    ['PostgreSQL', 'CREATE DATABASE dbname;', 'ساخت دیتابیس جدید', 'create,database,new', 'CREATE DATABASE myapp;', 'createdb, DROP DATABASE'],
    
    ['PostgreSQL', 'DROP DATABASE dbname;', 'حذف دیتابیس', 'delete,remove,database', 'DROP DATABASE IF EXISTS myapp;', 'CREATE DATABASE'],
    
    ['PostgreSQL', 'CREATE USER username WITH PASSWORD \'pass\';', 'ساخت کاربر جدید', 'user,create,role', "CREATE USER john WITH PASSWORD '123456';", 'ALTER USER, DROP USER'],
    
    ['PostgreSQL', 'GRANT ALL PRIVILEGES ON DATABASE dbname TO username;', 'دادن دسترسی به کاربر', 'permissions,access,grant', 'GRANT ALL PRIVILEGES ON DATABASE mydb TO john;', 'REVOKE'],
    
    ['PostgreSQL', '\\q', 'خروج از محیط psql', 'exit,quit,logout', '\\q', 'exit, Ctrl+D'],
    
    ['PostgreSQL', 'pg_dump dbname > backup.sql', 'بکاپ گرفتن از دیتابیس', 'backup,export,dump', 'pg_dump mydb > backup.sql', 'pg_dumpall, pg_restore'],
    
    ['PostgreSQL', 'pg_dumpall > all_backup.sql', 'بکاپ کامل از همه دیتابیس‌ها', 'backup,full,all', 'pg_dumpall > all_backup.sql', 'pg_dump'],
    
    ['PostgreSQL', 'pg_restore -d dbname backup.sql', 'بازگردانی بکاپ', 'restore,import,recover', 'pg_restore -d mydb backup.sql', 'psql < backup.sql'],
    
    ['PostgreSQL', 'psql -d dbname -f script.sql', 'اجرای فایل SQL', 'execute,run,script', 'psql -d mydb -f init.sql', '\\i script.sql'],
    
    ['PostgreSQL', 'SELECT version();', 'نمایش نسخه PostgreSQL', 'version,info,status', 'SELECT version();', 'SHOW server_version;'],
    
    ['PostgreSQL', '\\timing', 'فعال کردن زمان اجرای کوئری‌ها', 'timer,performance,query', '\\timing', 'EXPLAIN ANALYZE'],
    
    ['PostgreSQL', 'EXPLAIN ANALYZE SELECT * FROM table;', 'تحلیل و بهینه‌سازی کوئری', 'performance,analyze,optimize', 'EXPLAIN ANALYZE SELECT * FROM users WHERE age > 18;', 'EXPLAIN, \\timing'],
    
    ['PostgreSQL', 'VACUUM;', 'پاکسازی و بهینه‌سازی دیتابیس', 'clean,optimize,maintenance', 'VACUUM ANALYZE;', 'VACUUM FULL, ANALYZE'],
    
    ['PostgreSQL', 'pg_stat_activity', 'مشاهده کوئری‌های در حال اجرا', 'running queries,monitor', 'SELECT * FROM pg_stat_activity;', 'pg_locks'],
];

// افزودن دسته‌بندی
$stmt = $pdo->prepare("INSERT IGNORE INTO categories (category) VALUES ('PostgreSQL')");
$stmt->execute();

$success = 0;
foreach($postgresql_commands as $cmd) {
    $check = $pdo->prepare("SELECT COUNT(*) FROM commands WHERE command = ? AND category = 'PostgreSQL'");
    $check->execute([$cmd[1]]);
    
    if($check->fetchColumn() == 0) {
        $stmt = $pdo->prepare("INSERT INTO commands (category, command, description, keywords, example, similar_commands) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute($cmd);
        $success++;
    }
}

echo "✅ $success دستور PostgreSQL با موفقیت اضافه شد!";
?>