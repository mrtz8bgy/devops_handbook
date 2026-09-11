<?php
// ============================================================
// DevOps Handbook v3 — بوت‌استرپ مشترک
// در انتهای هر config.php لود می‌شود. $pdo باید از قبل ساخته شده باشد.
// ============================================================
if (defined('DH_BOOT')) return;
define('DH_BOOT', true);
define('DH_VERSION', '3.0');

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/search_engine.php';
require_once __DIR__ . '/chatbot_engine.php';

ensure_session();
