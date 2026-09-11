<?php
require_once 'config.php';
require_once __DIR__ . '/../../includes/bootstrap.php';
require_admin($pdo); // v3: فقط ادمین

$redis_commands = [
    ['Redis', 'redis-server', 'اجرای سرور Redis', 'start,server,database', 'redis-server --port 6379', 'redis-server /etc/redis.conf'],
    
    ['Redis', 'redis-cli', 'اتصال به Redis CLI', 'connect,client,shell', 'redis-cli -h localhost -p 6379', 'redis-cli -a password'],
    
    ['Redis', 'SET key value', 'ذخیره مقدار در کلید', 'store,save,insert', 'SET username "John"', 'SETEX, SETNX'],
    
    ['Redis', 'GET key', 'دریافت مقدار کلید', 'retrieve,value,read', 'GET username', 'MGET, GETSET'],
    
    ['Redis', 'DEL key', 'حذف کلید', 'delete,remove,erase', 'DEL username', 'UNLINK, FLUSHDB'],
    
    ['Redis', 'EXISTS key', 'بررسی وجود کلید', 'check,exists,has', 'EXISTS username', 'TYPE, TTL'],
    
    ['Redis', 'EXPIRE key seconds', 'تنظیم زمان انقضا', 'ttl,timeout,expiry', 'EXPIRE session 3600', 'PEXPIRE, EXPIREAT'],
    
    ['Redis', 'KEYS pattern', 'جستجوی کلیدها', 'search,find,pattern', 'KEYS user:*', 'SCAN'],
    
    ['Redis', 'HSET key field value', 'ذخیره در هش', 'hash,object,store', 'HSET user:100 name "John" age 30', 'HGET, HMSET'],
    
    ['Redis', 'HGET key field', 'دریافت از هش', 'hash,retrieve,field', 'HGET user:100 name', 'HGETALL, HMGET'],
    
    ['Redis', 'LPUSH key value', 'افزودن به لیست (سمت چپ)', 'list,push,queue', 'LPUSH tasks "task1"', 'RPUSH, LPOP'],
    
    ['Redis', 'SAVE', 'ذخیره لحظه‌ای دیتا در دیسک', 'backup,persist,snapshot', 'SAVE', 'BGSAVE, LASTSAVE'],
];

$stmt = $pdo->prepare("INSERT IGNORE INTO categories (category) VALUES ('Redis')");
$stmt->execute();

$success = 0;
foreach($redis_commands as $cmd) {
    $check = $pdo->prepare("SELECT COUNT(*) FROM commands WHERE command = ? AND category = 'Redis'");
    $check->execute([$cmd[1]]);
    
    if($check->fetchColumn() == 0) {
        $stmt = $pdo->prepare("INSERT INTO commands (category, command, description, keywords, example, similar_commands) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute($cmd);
        $success++;
    }
}

echo "✅ $success دستور Redis با موفقیت اضافه شد!";
?>