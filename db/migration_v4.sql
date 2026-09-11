-- ============================================================
-- DevOps Handbook v4 — هوش مصنوعی Fallback + یادگیری خودکار
-- ============================================================

-- صف پیش‌نویس‌های AI (منتظر تأیید ادمین)
CREATE TABLE IF NOT EXISTS `ai_drafts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `question` TEXT NOT NULL,
  `nq` VARCHAR(255) NOT NULL DEFAULT '',
  `answer` MEDIUMTEXT NULL,
  `provider` VARCHAR(30) NULL,
  `model` VARCHAR(100) NULL,
  `status` ENUM('queued','ready','approved','rejected','failed') NOT NULL DEFAULT 'queued',
  `error` VARCHAR(255) NULL,
  `tries` TINYINT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `answered_at` TIMESTAMP NULL,
  KEY `idx_nq` (`nq`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- لاگ مصرف AI (کنترل سقف روزانه)
CREATE TABLE IF NOT EXISTS `ai_usage_log` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `draft_id` INT NULL,
  `provider` VARCHAR(30) NOT NULL,
  `model` VARCHAR(100) NOT NULL DEFAULT '',
  `duration_ms` INT NOT NULL DEFAULT 0,
  `answer_chars` INT NOT NULL DEFAULT 0,
  `ok` TINYINT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- تنظیمات کلیدی-مقداری
CREATE TABLE IF NOT EXISTS `settings` (
  `k` VARCHAR(64) PRIMARY KEY,
  `v` TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- پیش‌فرض‌های هوش مصنوعی (کلید API فقط از ENV خوانده می‌شود)
INSERT IGNORE INTO `settings` (`k`, `v`) VALUES
('ai_enabled', '1'),
('ai_provider', 'ollama'),
('ai_ollama_url', 'http://localhost:11434'),
('ai_ollama_model', 'qwen2.5:3b'),
('ai_api_url', 'https://api.openai.com/v1'),
('ai_api_model', 'gpt-4o-mini'),
('ai_daily_limit', '50'),
('ai_timeout', '90');
