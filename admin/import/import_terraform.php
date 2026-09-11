<?php
require_once 'config.php';

$terraform_commands = [
    ['Terraform', 'terraform init', 'آماده‌سازی دایرکتوری و دانلود پلاگین‌ها', 'initialize,download,plugins', 'terraform init -upgrade', 'terraform get'],
    
    ['Terraform', 'terraform plan', 'نمایش تغییرات پیش از اجرا', 'plan,preview,dry-run', 'terraform plan -out=tfplan', 'terraform show'],
    
    ['Terraform', 'terraform apply', 'اعمال تغییرات روی زیرساخت', 'apply,deploy,create', 'terraform apply -auto-approve', 'terraform apply tfplan'],
    
    ['Terraform', 'terraform destroy', 'حذف کامل زیرساخت', 'destroy,delete,cleanup', 'terraform destroy -auto-approve', 'terraform apply -destroy'],
    
    ['Terraform', 'terraform validate', 'اعتبارسنجی فایل‌های کانفیگ', 'validate,check,syntax', 'terraform validate', 'terraform fmt -check'],
    
    ['Terraform', 'terraform fmt', 'فرمت کردن فایل‌ها استاندارد', 'format,style,beautify', 'terraform fmt -recursive', 'terraform fmt -diff'],
    
    ['Terraform', 'terraform show', 'نمایش وضعیت فعلی', 'state,show,output', 'terraform show -json', 'terraform state list'],
    
    ['Terraform', 'terraform state list', 'لیست منابع در state', 'resources,state,list', 'terraform state list', 'terraform state show'],
    
    ['Terraform', 'terraform output', 'نمایش outputها', 'output,values,variables', 'terraform output instance_ip', 'terraform output -json'],
    
    ['Terraform', 'terraform workspace new dev', 'ساخت workspace جدید', 'workspace,environment,isolate', 'terraform workspace new production', 'terraform workspace select'],
    
    ['Terraform', 'terraform plan -destroy', 'برنامه ریزی برای حذف', 'destroy,delete,plan', 'terraform plan -destroy -out=destroy.tfplan', 'terraform destroy'],
    
    ['Terraform', 'terraform refresh', 'به‌روزرسانی state با منابع واقعی', 'refresh,sync,update', 'terraform refresh', 'terraform apply -refresh-only'],
];

$stmt = $pdo->prepare("INSERT IGNORE INTO categories (category) VALUES ('Terraform')");
$stmt->execute();

$success = 0;
foreach($terraform_commands as $cmd) {
    $check = $pdo->prepare("SELECT COUNT(*) FROM commands WHERE command = ? AND category = 'Terraform'");
    $check->execute([$cmd[1]]);
    
    if($check->fetchColumn() == 0) {
        $stmt = $pdo->prepare("INSERT INTO commands (category, command, description, keywords, example, similar_commands) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute($cmd);
        $success++;
    }
}

echo "✅ $success دستور Terraform با موفقیت اضافه شد!";
?>