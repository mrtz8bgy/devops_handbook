<?php
require_once 'config.php';
require_once __DIR__ . '/../../includes/bootstrap.php';
require_admin($pdo); // v3: فقط ادمین

$k8s_advanced_commands = [
    ['Kubernetes', 'kubectl get pods --all-namespaces', 'لیست پادها در همه نام‌فضاها', 'pods,all,wide', 'kubectl get pods -A -o wide', 'kubectl get pods -n namespace'],
    
    ['Kubernetes', 'kubectl describe pod pod-name', 'جزئیات کامل یک پاد', 'describe,details,info', 'kubectl describe pod nginx-pod', 'kubectl get pod -o yaml'],
    
    ['Kubernetes', 'kubectl logs -f pod-name', 'مشاهده لحظه‌ای لاگ پاد', 'logs,stream,follow', 'kubectl logs -f nginx-pod', 'kubectl logs pod-name --previous'],
    
    ['Kubernetes', 'kubectl exec -it pod-name -- /bin/bash', 'ورود به شل پاد', 'exec,shell,interactive', 'kubectl exec -it nginx-pod -- /bin/bash', 'kubectl attach'],
    
    ['Kubernetes', 'kubectl port-forward pod-name 8080:80', 'فوروارد کردن پورت به پاد', 'port,forward,tunnel', 'kubectl port-forward nginx-pod 8080:80', 'kubectl proxy'],
    
    ['Kubernetes', 'kubectl apply -f deployment.yaml', 'اعمال کانفیگ از فایل', 'apply,create,deploy', 'kubectl apply -f nginx-deployment.yaml', 'kubectl create -f'],
    
    ['Kubernetes', 'kubectl delete -f deployment.yaml', 'حذف منابع از فایل', 'delete,remove,clean', 'kubectl delete -f nginx-deployment.yaml', 'kubectl delete pod pod-name'],
    
    ['Kubernetes', 'kubectl rollout status deployment/nginx', 'وضعیت رول اوت', 'rollout,status,progress', 'kubectl rollout status deployment/nginx', 'kubectl rollout history'],
    
    ['Kubernetes', 'kubectl rollout undo deployment/nginx', 'برگرداندن به نسخه قبل', 'rollback,undo,revert', 'kubectl rollout undo deployment/nginx', 'kubectl rollout history'],
    
    ['Kubernetes', 'kubectl scale deployment/nginx --replicas=5', 'مقیاس دهی تعداد پادها', 'scale,replicas,horizontal', 'kubectl scale deployment/nginx --replicas=5', 'kubectl autoscale'],
    
    ['Kubernetes', 'kubectl get events --sort-by=.metadata.creationTimestamp', 'مشاهده رویدادها', 'events,logs,audit', 'kubectl get events --sort-by=.metadata.creationTimestamp', 'kubectl describe node'],
    
    ['Kubernetes', 'kubectl top pods', 'نمایش مصرف منابع پادها', 'metrics,cpu,memory', 'kubectl top pods --all-namespaces', 'kubectl top nodes'],
    
    ['Kubernetes', 'kubectl get configmap', 'لیست ConfigMap ها', 'configmap,configuration,env', 'kubectl get configmap', 'kubectl describe configmap'],
    
    ['Kubernetes', 'kubectl get secrets', 'لیست Secrets', 'secrets,password,token', 'kubectl get secrets', 'kubectl describe secret'],
    
    ['Kubernetes', 'kubectl create secret generic my-secret --from-literal=key=value', 'ساخت Secret جدید', 'create,secret,literal', 'kubectl create secret generic db-pass --from-literal=password=123', 'kubectl create secret docker-registry'],
    
    ['Kubernetes', 'kubectl get ingress', 'لیست Ingress ها', 'ingress,routing,dns', 'kubectl get ingress -A', 'kubectl describe ingress'],
    
    ['Kubernetes', 'kubectl get persistentvolume', 'لیست حجم‌های پایدار', 'pv,storage,volume', 'kubectl get pv', 'kubectl get pvc'],
    
    ['Kubernetes', 'kubectl cordon node-name', 'مسدود کردن زمانبندی روی نود', 'cordon,node,maintenance', 'kubectl cordon worker-node1', 'kubectl uncordon'],
    
    ['Kubernetes', 'kubectl drain node-name --ignore-daemonsets', 'تخلیه نود برای نگهداری', 'drain,maintenance,migrate', 'kubectl drain worker-node1 --ignore-daemonsets', 'kubectl delete node'],
    
    ['Kubernetes', 'kubectl get crd', 'لیست Custom Resource Definitions', 'crd,custom,resources', 'kubectl get crd', 'kubectl explain crd'],
];

$stmt = $pdo->prepare("INSERT IGNORE INTO categories (category) VALUES ('Kubernetes')");
$stmt->execute();

$success = 0;
foreach($k8s_advanced_commands as $cmd) {
    $check = $pdo->prepare("SELECT COUNT(*) FROM commands WHERE command = ? AND category = 'Kubernetes'");
    $check->execute([$cmd[1]]);
    
    if($check->fetchColumn() == 0) {
        $stmt = $pdo->prepare("INSERT INTO commands (category, command, description, keywords, example, similar_commands) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute($cmd);
        $success++;
    }
}

echo "✅ $success دستور پیشرفته Kubernetes با موفقیت اضافه شد!";
?>