<?php
require_once 'config.php';
require_once __DIR__ . '/../../includes/bootstrap.php';
require_admin($pdo); // v3: فقط ادمین

$ansible_commands = [
    ['Ansible', 'ansible --version', 'نمایش نسخه Ansible', 'version,info', 'ansible --version', 'ansible-config dump'],
    
    ['Ansible', 'ansible all -m ping', 'پینگ به همه سرورها', 'test,connectivity,ping', 'ansible all -m ping -i inventory.ini', 'ansible-playbook'],
    
    ['Ansible', 'ansible-playbook playbook.yml', 'اجرای پلی بوک', 'run,playbook,execute', 'ansible-playbook site.yml --check', 'ansible-pull'],
    
    ['Ansible', 'ansible-doc module_name', 'مشاهده مستندات ماژول', 'documentation,help,module', 'ansible-doc copy', 'ansible-doc -l'],
    
    ['Ansible', 'ansible-galaxy init role_name', 'ساخت نقش جدید', 'create,scaffold,role', 'ansible-galaxy init myrole', 'ansible-galaxy install'],
    
    ['Ansible', 'ansible-vault encrypt secret.yml', 'رمزنگاری فایل', 'encrypt,secure,password', 'ansible-vault encrypt secrets.yml', 'ansible-vault decrypt'],
    
    ['Ansible', 'ansible-inventory --list', 'لیست موجودی سرورها', 'inventory,hosts,list', 'ansible-inventory --list -i inventory.ini', 'ansible all --list-hosts'],
    
    ['Ansible', 'ansible-config dump', 'نمایش تنظیمات فعلی', 'config,settings,show', 'ansible-config dump --only-changed', 'ansible --version'],
    
    ['Ansible', 'ansible webservers -m apt -a "name=nginx state=present"', 'نصب پکیج با apt', 'install,package,apt', 'ansible webservers -m apt -a "name=nginx state=present"', 'ansible webservers -m yum'],
    
    ['Ansible', 'ansible all -m copy -a "src=/local/file dest=/remote/file"', 'کپی فایل به سرورها', 'copy,file,transfer', 'ansible all -m copy -a "src=/etc/hosts dest=/tmp/hosts"', 'ansible all -m fetch'],
    
    ['Ansible', 'ansible all -m shell -a "uptime"', 'اجرای دستور shell', 'command,execute,shell', 'ansible all -m shell -a "df -h"', 'ansible all -m command'],
    
    ['Ansible', 'ansible-playbook playbook.yml --check', 'حالت Dry-run', 'check,test,simulate', 'ansible-playbook playbook.yml --check', 'ansible-playbook playbook.yml --diff'],
    
    ['Ansible', 'ansible-playbook playbook.yml --tags "setup"', 'اجرای فقط تگ مشخص', 'tags,filter,selective', 'ansible-playbook playbook.yml --tags "install,config"', 'ansible-playbook playbook.yml --skip-tags'],
    
    ['Ansible', 'ansible-playbook playbook.yml -v', 'حالت verbose برای دیباگ', 'verbose,debug,output', 'ansible-playbook playbook.yml -vvv', 'ansible-playbook playbook.yml --step'],
    
    ['Ansible', 'ansible all -m setup | grep ansible_os_family', 'نمایش facts سرورها', 'facts,info,system', 'ansible all -m setup | grep "os_family"', 'ansible all -m gather_facts'],
];

$stmt = $pdo->prepare("INSERT IGNORE INTO categories (category) VALUES ('Ansible')");
$stmt->execute();

$success = 0;
foreach($ansible_commands as $cmd) {
    $check = $pdo->prepare("SELECT COUNT(*) FROM commands WHERE command = ? AND category = 'Ansible'");
    $check->execute([$cmd[1]]);
    
    if($check->fetchColumn() == 0) {
        $stmt = $pdo->prepare("INSERT INTO commands (category, command, description, keywords, example, similar_commands) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute($cmd);
        $success++;
    }
}

echo "✅ $success دستور Ansible با موفقیت اضافه شد!";
?>