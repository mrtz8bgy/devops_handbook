<?php
// ثبت فیدبک چت‌بات — POST {qa_id, feedback}
require_once __DIR__ . '/../config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['error' => 'POST only'], 405);
$in = json_decode(file_get_contents('php://input'), true) ?: $_POST;
$ok = save_feedback($pdo, (int)($in['qa_id'] ?? 0), $_SESSION['chat_session_id'] ?? session_id(), $in['feedback'] ?? '');
json_response(['ok' => $ok]);
