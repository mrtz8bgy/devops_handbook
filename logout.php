<?php
require_once __DIR__ . '/config.php';
logout_user();
safe_redirect('index.php');
