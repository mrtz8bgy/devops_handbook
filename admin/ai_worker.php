<?php
// ============================================================
// DevOps Handbook v4 — ورکر پس‌زمینه تولید پاسخ AI (فقط CLI)
// اجرا: php admin/ai_worker.php <draft_id>
// ============================================================
if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    die('CLI only');
}
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../includes/ai_provider.php';

$id = (int)($argv[1] ?? 0);
if ($id <= 0) { fwrite(STDERR, "draft id required\n"); exit(1); }

$st = $pdo->prepare("SELECT * FROM ai_drafts WHERE id = ? AND status IN ('queued','failed')");
$st->execute([$id]);
$row = $st->fetch(PDO::FETCH_ASSOC);
if (!$row) exit(0); // قبلاً انجام شده

$res = ai_generate($pdo, $row['question'], $id);
if ($res['ok']) {
    $pdo->prepare("UPDATE ai_drafts SET answer = ?, provider = ?, model = ?, status = 'ready', error = NULL, answered_at = NOW() WHERE id = ?")
        ->execute([$res['answer'], $res['provider'], $res['model'], $id]);
    echo "draft $id ready ({$res['model']}, {$res['ms']}ms)\n";
} else {
    $tries = (int)$row['tries'] + 1;
    $status = $tries >= 3 ? 'failed' : 'queued'; // ۳ بار تلاش، بعد failed
    $pdo->prepare("UPDATE ai_drafts SET tries = ?, error = ?, status = ? WHERE id = ?")
        ->execute([$tries, mb_substr($res['error'], 0, 250), $status, $id]);
    echo "draft $id error: {$res['error']}\n";
    // اگر کاملاً ناموفق بود، در بی‌جواب‌های عادی هم ثبت شود تا گم نشود
    if ($status === 'failed') {
        require_once __DIR__ . '/../includes/chatbot_engine.php';
        if (function_exists('log_unknown')) log_unknown($pdo, $row['question']);
    }
}
