<?php
// ============================================================
// DevOps Handbook v4 — API تولید دستی پاسخ AI (فقط ادمین، AJAX)
// POST: csrf, id
// ============================================================
require_once __DIR__ . '/../admin/config.php';
require_once __DIR__ . '/../includes/ai_provider.php';
header('Content-Type: application/json; charset=utf-8');

if (!is_admin($pdo)) { http_response_code(403); echo json_encode(['ok' => false, 'error' => '⛔ فقط ادمین']); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_verify()) {
    http_response_code(400); echo json_encode(['ok' => false, 'error' => 'درخواست نامعتبر']); exit;
}
$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) { echo json_encode(['ok' => false, 'error' => 'شناسه نامعتبر']); exit; }

set_time_limit(300);
$st = $pdo->prepare("SELECT * FROM ai_drafts WHERE id = ? AND status IN ('queued','failed','ready')");
$st->execute([$id]);
$row = $st->fetch(PDO::FETCH_ASSOC);
if (!$row) { echo json_encode(['ok' => false, 'error' => 'پیش‌نویس پیدا نشد']); exit; }

$res = ai_generate($pdo, $row['question'], $id);
if ($res['ok']) {
    $pdo->prepare("UPDATE ai_drafts SET answer = ?, provider = ?, model = ?, status = 'ready', error = NULL, answered_at = NOW() WHERE id = ?")
        ->execute([$res['answer'], $res['provider'], $res['model'], $id]);
    echo json_encode(['ok' => true, 'model' => $res['model'], 'ms' => $res['ms']], JSON_UNESCAPED_UNICODE);
} else {
    $tries = (int)$row['tries'] + 1;
    $pdo->prepare("UPDATE ai_drafts SET tries = ?, error = ? WHERE id = ?")
        ->execute([$tries, mb_substr($res['error'], 0, 250), $id]);
    echo json_encode(['ok' => false, 'error' => $res['error']], JSON_UNESCAPED_UNICODE);
}
