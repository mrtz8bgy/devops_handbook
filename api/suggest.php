<?php
// پیشنهاد خودکار جستجو — GET ?q=...
require_once __DIR__ . '/../config.php';
json_response(suggest_commands($pdo, $_GET['q'] ?? '', 8));
