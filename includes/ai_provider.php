<?php
// ============================================================
// DevOps Handbook v4 — ارائه‌دهنده هوش مصنوعی (Ollama / OpenAI / Mock)
// رابط مشترک: با یک تنظیم سوییچ می‌شود. کلید API فقط از ENV.
// ============================================================
if (defined('DH_AI')) return;
define('DH_AI', true);
if (!function_exists('fa_normalize')) require_once __DIR__ . '/helpers.php';

function ai_defaults() {
    return [
        'ai_enabled' => '1', 'ai_provider' => 'ollama',
        'ai_ollama_url' => 'http://localhost:11434', 'ai_ollama_model' => 'qwen2.5:3b',
        'ai_api_url' => 'https://api.openai.com/v1', 'ai_api_model' => 'gpt-4o-mini',
        'ai_daily_limit' => '50', 'ai_timeout' => '90',
    ];
}

/** تنظیمات = دیتابیس + ENV (ENV مقدم است؛ کلید فقط ENV) */
function ai_settings($pdo) {
    $s = ai_defaults();
    try {
        $rows = $pdo->query("SELECT k, v FROM settings")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $r) { if (array_key_exists($r['k'], $s)) $s[$r['k']] = (string)$r['v']; }
    } catch (Exception $e) { /* جدول هنوز نیست */ }
    $map = ['AI_ENABLED' => 'ai_enabled', 'AI_PROVIDER' => 'ai_provider',
        'AI_OLLAMA_URL' => 'ai_ollama_url', 'AI_OLLAMA_MODEL' => 'ai_ollama_model',
        'AI_API_URL' => 'ai_api_url', 'AI_API_MODEL' => 'ai_api_model',
        'AI_DAILY_LIMIT' => 'ai_daily_limit', 'AI_TIMEOUT' => 'ai_timeout'];
    foreach ($map as $env => $k) {
        $v = getenv($env);
        if ($v !== false && $v !== '') $s[$k] = $v;
    }
    $s['ai_api_key'] = getenv('AI_API_KEY') ?: '';
    return $s;
}

/** ذخیره تنظیمات ادمین (فقط کلیدهای مجاز، بدون کلید API) */
function ai_save_settings($pdo, $arr) {
    $allow = ['ai_enabled', 'ai_provider', 'ai_ollama_url', 'ai_ollama_model', 'ai_api_url', 'ai_api_model', 'ai_daily_limit', 'ai_timeout'];
    $st = $pdo->prepare("INSERT INTO settings (k, v) VALUES (?, ?) ON DUPLICATE KEY UPDATE v = VALUES(v)");
    foreach ($allow as $k) {
        if (!isset($arr[$k])) continue;
        $v = trim((string)$arr[$k]);
        if ($k === 'ai_provider' && !in_array($v, ['ollama', 'openai', 'mock'], true)) continue;
        if ($k === 'ai_enabled') $v = ($v === '1') ? '1' : '0';
        if (in_array($k, ['ai_daily_limit', 'ai_timeout'], true)) $v = (string)max(1, min(1000, (int)$v));
        $st->execute([$k, mb_substr($v, 0, 500)]);
    }
}

/** تعداد درخواست‌های موفق امروز */
function ai_today_count($pdo) {
    try {
        return (int)$pdo->query("SELECT COUNT(*) FROM ai_usage_log WHERE ok = 1 AND created_at >= CURDATE()")->fetchColumn();
    } catch (Exception $e) { return 0; }
}

function ai_system_prompt() {
    return 'تو «دستیار DevOps» در یک کتابخانه فارسی دستورات هستی. به فارسی، کوتاه و کاربردی جواب بده (حداکثر ۱۵۰ کلمه). '
        . 'اگر سؤال درباره دستور خاصی است: اول خود دستور را بده، بعد یک مثال کاربردی و یک خط توضیح. '
        . 'اگر سؤال نامرتبط با DevOps، لینوکس یا برنامه‌نویسی است، مؤدبانه بگو فقط در این حوزه‌ها کمک می‌کنی. ایموجی کم استفاده کن.';
}

/**
 * تولید پاسخ — ['ok','answer','error','provider','model','ms']
 * سقف روزانه و لاگ مصرف داخل همین تابع کنترل می‌شود.
 */
function ai_generate($pdo, $question, $draft_id = null, $context = '') {
    $s = ai_settings($pdo);
    $provider = $s['ai_provider'];
    $model = $provider === 'openai' ? $s['ai_api_model'] : ($provider === 'ollama' ? $s['ai_ollama_model'] : 'mock-1');
    $t0 = microtime(true);
    $done = function ($ok, $answer = '', $error = '') use ($pdo, $provider, $model, $t0, $draft_id) {
        $ms = (int)((microtime(true) - $t0) * 1000);
        try {
            $pdo->prepare("INSERT INTO ai_usage_log (draft_id, provider, model, duration_ms, answer_chars, ok) VALUES (?,?,?,?,?,?)")
                ->execute([$draft_id, $provider, $model, $ms, mb_strlen($answer), $ok ? 1 : 0]);
        } catch (Exception $e) {}
        return ['ok' => $ok, 'answer' => $answer, 'error' => $error, 'provider' => $provider, 'model' => $model, 'ms' => $ms];
    };

    if (($s['ai_enabled'] ?? '0') !== '1') return $done(false, '', 'AI خاموش است');
    if (ai_today_count($pdo) >= (int)($s['ai_daily_limit'] ?? 50)) return $done(false, '', 'سقف روزانه تمام شد');

    // --- حالت آزمایشی (بدون نیاز به سرویس) ---
    if ($provider === 'mock') {
        $a = "🤖 [پاسخ آزمایشی mock] سؤال «{$question}» ثبت شد. این متن نمونه است — provider را روی ollama یا openai بگذار تا جواب واقعی بگیری.";
        return $done(true, $a);
    }

    if (!function_exists('curl_init')) return $done(false, '', 'افزونه cURL نصب نیست');

    $timeout = max(10, min(600, (int)($s['ai_timeout'] ?? 90)));
    $user_prompt = $context !== ''
        ? "زمینه:\n{$context}\n\nسؤال کاربر: {$question}"
        : "سؤال کاربر: {$question}";

    // --- Ollama ---
    if ($provider === 'ollama') {
        $ch = curl_init(rtrim($s['ai_ollama_url'], '/') . '/api/generate');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true, CURLOPT_TIMEOUT => $timeout,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode([
                'model' => $model, 'stream' => false,
                'system' => ai_system_prompt(), 'prompt' => $user_prompt,
                'options' => ['temperature' => 0.3, 'num_predict' => 500],
            ], JSON_UNESCAPED_UNICODE),
        ]);
        $raw = curl_exec($ch);
        $err = curl_error($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($raw === false) return $done(false, '', 'اولاما در دسترس نیست: ' . mb_substr($err, 0, 120));
        $j = json_decode($raw, true);
        $ans = trim($j['response'] ?? '');
        if ($code !== 200 || $ans === '') return $done(false, '', 'خطای اولاما (کد ' . $code . ')');
        return $done(true, $ans);
    }

    // --- OpenAI-compatible ---
    if ($provider === 'openai') {
        if ($s['ai_api_key'] === '') return $done(false, '', 'کلید API (AI_API_KEY) تنظیم نشده');
        $ch = curl_init(rtrim($s['ai_api_url'], '/') . '/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true, CURLOPT_TIMEOUT => $timeout,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: Bearer ' . $s['ai_api_key']],
            CURLOPT_POSTFIELDS => json_encode([
                'model' => $model, 'temperature' => 0.3, 'max_tokens' => 600,
                'messages' => [
                    ['role' => 'system', 'content' => ai_system_prompt()],
                    ['role' => 'user', 'content' => $user_prompt],
                ],
            ], JSON_UNESCAPED_UNICODE),
        ]);
        $raw = curl_exec($ch);
        $err = curl_error($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($raw === false) return $done(false, '', 'خطای شبکه: ' . mb_substr($err, 0, 120));
        $j = json_decode($raw, true);
        $ans = trim($j['choices'][0]['message']['content'] ?? '');
        if ($code !== 200 || $ans === '') {
            $e = $j['error']['message'] ?? ('کد ' . $code);
            return $done(false, '', 'خطای API: ' . mb_substr($e, 0, 120));
        }
        return $done(true, $ans);
    }

    return $done(false, '', 'provider نامعتبر');
}

/** ثبت سؤال در صف (با حذف تکراری) — ['id','status','is_new'] */
function ai_queue($pdo, $question) {
    $nq = mb_substr(fa_normalize($question), 0, 250);
    try {
        $st = $pdo->prepare("SELECT id, status FROM ai_drafts WHERE nq = ? AND status IN ('queued','ready','failed') ORDER BY id DESC LIMIT 1");
        $st->execute([$nq]);
        if ($ex = $st->fetch(PDO::FETCH_ASSOC)) return ['id' => (int)$ex['id'], 'status' => $ex['status'], 'is_new' => false];
        $st = $pdo->prepare("INSERT INTO ai_drafts (question, nq) VALUES (?, ?)");
        $st->execute([$question, $nq]);
        return ['id' => (int)$pdo->lastInsertId(), 'status' => 'queued', 'is_new' => true];
    } catch (Exception $e) {
        return ['id' => 0, 'status' => 'error', 'is_new' => false];
    }
}

/** تلاش برای تولید پس‌زمینه (بدون معطل کردن کاربر) */
function ai_try_background($draft_id) {
    if (!function_exists('exec')) return false;
    $dis = array_map('trim', explode(',', (string)ini_get('disable_functions')));
    if (in_array('exec', $dis, true)) return false;
    $php = defined('PHP_BINARY') && PHP_BINARY ? PHP_BINARY : 'php';
    $cmd = escapeshellcmd($php) . ' ' . escapeshellarg(__DIR__ . '/../admin/ai_worker.php') . ' ' . (int)$draft_id . ' > /dev/null 2>&1 &';
    @exec($cmd);
    return true;
}

/**
 * هوک Fallback چت‌بات — متن پیام به کاربر، یا null (AI خاموش/سقف پر → رفتار عادی unknown)
 */
function ai_fallback($pdo, $q) {
    $s = ai_settings($pdo);
    if (($s['ai_enabled'] ?? '0') !== '1') return null;
    if (ai_today_count($pdo) >= (int)($s['ai_daily_limit'] ?? 50)) return null;
    $r = ai_queue($pdo, $q);
    if ($r['id'] <= 0) return null;
    if ($r['status'] === 'ready') {
        return '🤖 این سؤال قبلاً با هوش مصنوعی جواب داده شده و <b>منتظر تأیید مدیر</b> است — به‌زودی اضافه می‌شود! ✅';
    }
    if ($r['is_new']) ai_try_background($r['id']);
    return '🤖 سؤال جالبیه! جوابش رو با هوش مصنوعی آماده می‌کنم و می‌فرستم برای تأیید مدیر.<br>✅ سؤالت ثبت شد — به‌زودی همین‌جا جوابش رو می‌بینی! فعلاً این‌ها رو امتحان کن:';
}
