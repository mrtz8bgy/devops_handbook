<?php
require_once 'config.php';

header('Content-Type: application/json');

$stmt = $pdo->query("SELECT category FROM categories ORDER BY category");
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo json_encode($categories);
?>