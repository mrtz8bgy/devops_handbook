<?php
require_once 'config.php';

$mongodb_commands = [
    ['MongoDB', 'mongod', 'اجرای سرور MongoDB', 'start,server,database', 'mongod --dbpath /data/db', 'mongos, mongo'],
    
    ['MongoDB', 'mongo', 'اتصال به شل MongoDB', 'connect,shell,client', 'mongo mongodb://localhost:27017', 'mongosh'],
    
    ['MongoDB', 'show dbs', 'لیست تمام دیتابیس‌ها', 'databases,list,show', 'show dbs', 'db.adminCommand'],
    
    ['MongoDB', 'use dbname', 'تغییر یا ساخت دیتابیس', 'switch,create,database', 'use mydb', 'show dbs'],
    
    ['MongoDB', 'show collections', 'لیست مجموعه‌ها', 'tables,collections,list', 'show collections', 'db.getCollectionNames()'],
    
    ['MongoDB', 'db.collection.find()', 'جستجو در مجموعه', 'query,find,search', 'db.users.find({age: {\$gt: 18}})', 'db.collection.findOne()'],
    
    ['MongoDB', 'db.collection.insertOne()', 'درج یک سند', 'insert,add,create', 'db.users.insertOne({name: "John", age: 30})', 'db.collection.insertMany()'],
    
    ['MongoDB', 'db.collection.updateOne()', 'به‌روزرسانی یک سند', 'update,modify,edit', 'db.users.updateOne({name: "John"}, {\$set: {age: 31}})', 'db.collection.updateMany()'],
    
    ['MongoDB', 'db.collection.deleteOne()', 'حذف یک سند', 'delete,remove,erase', 'db.users.deleteOne({name: "John"})', 'db.collection.deleteMany()'],
    
    ['MongoDB', 'db.collection.countDocuments()', 'تعداد اسناد', 'count,total,size', 'db.users.countDocuments({age: {\$gt: 18}})', 'db.collection.estimatedDocumentCount()'],
    
    ['MongoDB', 'db.collection.createIndex()', 'ساخت ایندکس', 'index,optimize,performance', 'db.users.createIndex({email: 1})', 'db.collection.getIndexes()'],
    
    ['MongoDB', 'db.collection.aggregate()', 'aggregation pipeline', 'aggregate,group,calculate', 'db.orders.aggregate([{\$group: {_id: "\$status", total: {\$sum: 1}}}])', 'db.collection.mapReduce()'],
    
    ['MongoDB', 'mongodump', 'بکاپ گرفتن از دیتابیس', 'backup,export,dump', 'mongodump --db mydb --out /backup', 'mongorestore'],
    
    ['MongoDB', 'mongorestore', 'بازگردانی بکاپ', 'restore,import,recover', 'mongorestore --db mydb /backup/mydb', 'mongodump'],
    
    ['MongoDB', 'db.stats()', 'آمار دیتابیس', 'statistics,size,info', 'db.stats()', 'db.collection.stats()'],
];

$stmt = $pdo->prepare("INSERT IGNORE INTO categories (category) VALUES ('MongoDB')");
$stmt->execute();

$success = 0;
foreach($mongodb_commands as $cmd) {
    $check = $pdo->prepare("SELECT COUNT(*) FROM commands WHERE command = ? AND category = 'MongoDB'");
    $check->execute([$cmd[1]]);
    
    if($check->fetchColumn() == 0) {
        $stmt = $pdo->prepare("INSERT INTO commands (category, command, description, keywords, example, similar_commands) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute($cmd);
        $success++;
    }
}

echo "✅ $success دستور MongoDB با موفقیت اضافه شد!";
?>