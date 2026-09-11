<?php
// جستجوی JSON — GET ?q=&cat=&page=&per=
require_once __DIR__ . '/../config.php';
$q = trim($_GET['q'] ?? '');
$cat = trim($_GET['cat'] ?? '') ?: null;
$me = current_user($pdo);
$res = pro_search($pdo, $q, [
    'category' => $cat,
    'page' => (int)($_GET['page'] ?? 1),
    'per' => (int)($_GET['per'] ?? 12),
    'log' => true,
    'user_id' => $me['id'] ?? null,
]);
$res['results'] = array_map(fn($r) => [
    'id' => (int)$r['id'], 'command' => $r['command'], 'description' => $r['description'],
    'category' => $r['category'], 'example' => $r['example'] ?? '', 'score' => round($r['_w'] ?? 0, 1),
], $res['results']);
json_response($res);
