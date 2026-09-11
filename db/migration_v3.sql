-- ============================================================
-- DevOps Handbook v3 — Migration
-- اجرا: صفحه install.php (خودکار) یا ایمپورت دستی در phpMyAdmin
-- ============================================================

CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- مترادف‌های فنی چت‌بات (v3)
INSERT IGNORE INTO `chatbot_synonyms` (`word`, `synonym_for`, `category`) VALUES
('بکاپ', 'backup', 'tech'),
('پشتیبان', 'backup', 'tech'),
('پشتیبانگیری', 'backup', 'tech'),
('لاگ', 'log', 'tech'),
('کانتینر', 'container', 'tech'),
('ایمیج', 'image', 'tech'),
('دیتابیس', 'database', 'tech'),
('پایگاه داده', 'database', 'tech'),
('سرور', 'server', 'tech'),
('سرویس', 'service', 'tech'),
('گذرواژه', 'password', 'tech'),
('کاربر', 'user', 'tech');

CREATE TABLE IF NOT EXISTS `search_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NULL DEFAULT NULL,
  `query` varchar(200) NOT NULL,
  `results` int NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_query` (`query`(50)),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
