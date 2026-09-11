/*
 Navicat Premium Dump SQL

 Source Server         : localhost_3306
 Source Server Type    : MySQL
 Source Server Version : 100411 (10.4.11-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : devops_handbook

 Target Server Type    : MySQL
 Target Server Version : 100411 (10.4.11-MariaDB)
 File Encoding         : 65001

 Date: 05/05/2026 02:45:37
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for categories
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `category` varchar(100) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `category`(`category` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 43 CHARACTER SET = utf8 COLLATE = utf8_persian_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of categories
-- ----------------------------
INSERT INTO `categories` VALUES (1, 'Linux', '2026-04-30 22:23:42');
INSERT INTO `categories` VALUES (2, 'Docker', '2026-04-30 22:23:42');
INSERT INTO `categories` VALUES (3, 'Git', '2026-04-30 22:23:42');
INSERT INTO `categories` VALUES (4, 'Kubernetes', '2026-04-30 22:23:42');
INSERT INTO `categories` VALUES (5, 'Network', '2026-04-30 22:23:42');
INSERT INTO `categories` VALUES (6, 'Ansible', '2026-04-30 22:23:42');
INSERT INTO `categories` VALUES (7, 'Jenkins', '2026-04-30 22:23:42');
INSERT INTO `categories` VALUES (8, 'Python', '2026-04-30 22:23:42');
INSERT INTO `categories` VALUES (9, 'postgres', '2026-04-30 22:27:20');
INSERT INTO `categories` VALUES (17, 'MongoDB', '2026-04-30 23:00:39');
INSERT INTO `categories` VALUES (18, 'Nginx', '2026-04-30 23:00:45');
INSERT INTO `categories` VALUES (19, 'PostgreSQL', '2026-04-30 23:00:51');
INSERT INTO `categories` VALUES (20, 'Redis', '2026-04-30 23:00:55');
INSERT INTO `categories` VALUES (21, 'Terraform', '2026-04-30 23:00:59');
INSERT INTO `categories` VALUES (27, 'Database', '2026-05-01 01:11:04');
INSERT INTO `categories` VALUES (34, 'GitLab', '2026-05-05 00:10:33');
INSERT INTO `categories` VALUES (35, 'Jira', '2026-05-05 00:10:33');
INSERT INTO `categories` VALUES (36, 'Nexus', '2026-05-05 00:10:33');
INSERT INTO `categories` VALUES (37, 'SSL', '2026-05-05 00:10:33');

-- ----------------------------
-- Table structure for chatbot_conversation_context
-- ----------------------------
DROP TABLE IF EXISTS `chatbot_conversation_context`;
CREATE TABLE `chatbot_conversation_context`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `session_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `last_question` text CHARACTER SET utf8 COLLATE utf8_persian_ci NULL,
  `last_answer` text CHARACTER SET utf8 COLLATE utf8_persian_ci NULL,
  `topic` varchar(100) CHARACTER SET utf8 COLLATE utf8_persian_ci NULL DEFAULT NULL,
  `sentiment` varchar(50) CHARACTER SET utf8 COLLATE utf8_persian_ci NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_session`(`session_id` ASC) USING BTREE,
  INDEX `idx_topic`(`topic` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_persian_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chatbot_conversation_context
-- ----------------------------

-- ----------------------------
-- Table structure for chatbot_feedback
-- ----------------------------
DROP TABLE IF EXISTS `chatbot_feedback`;
CREATE TABLE `chatbot_feedback`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `qa_id` int NULL DEFAULT NULL,
  `session_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_persian_ci NULL DEFAULT NULL,
  `feedback` enum('good','bad','excellent','needs_improvement') CHARACTER SET utf8 COLLATE utf8_persian_ci NULL DEFAULT 'good',
  `comment` text CHARACTER SET utf8 COLLATE utf8_persian_ci NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_qa_id`(`qa_id` ASC) USING BTREE,
  CONSTRAINT `chatbot_feedback_ibfk_1` FOREIGN KEY (`qa_id`) REFERENCES `chatbot_qa` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_persian_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chatbot_feedback
-- ----------------------------
INSERT INTO `chatbot_feedback` VALUES (1, 209, 'setslqhqfqhric2u70sqok4531_1777933898', 'good', NULL, '2026-05-05 02:30:42');
INSERT INTO `chatbot_feedback` VALUES (2, 210, 'setslqhqfqhric2u70sqok4531_1777933898', 'excellent', NULL, '2026-05-05 02:40:28');

-- ----------------------------
-- Table structure for chatbot_qa
-- ----------------------------
DROP TABLE IF EXISTS `chatbot_qa`;
CREATE TABLE `chatbot_qa`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `question` varchar(500) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `answer` text CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `keywords` text CHARACTER SET utf8 COLLATE utf8_persian_ci NULL,
  `category` varchar(100) CHARACTER SET utf8 COLLATE utf8_persian_ci NULL DEFAULT NULL,
  `usage_count` int NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `weight` int NULL DEFAULT 1,
  `tags` text CHARACTER SET utf8 COLLATE utf8_persian_ci NULL,
  `sentiment` varchar(50) CHARACTER SET utf8 COLLATE utf8_persian_ci NULL DEFAULT 'neutral',
  `context_keywords` text CHARACTER SET utf8 COLLATE utf8_persian_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 211 CHARACTER SET = utf8 COLLATE = utf8_persian_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chatbot_qa
-- ----------------------------
INSERT INTO `chatbot_qa` VALUES (1, 'سلام', '? سلام عزیزم! چطور میتونم کمکت کنم؟', 'سلام, سلامتی, علیک', 'general', 4, '2026-05-05 00:33:05', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (2, 'چطوری', '? من خیلی خوبم! ممنون که پرسیدی. تو چطوری؟', 'چطوری, حالت چطوره, چه خبر', 'general', 2, '2026-05-05 00:33:05', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (3, 'ممنون', '? خواهش میکنم! هر وقت نیاز داشتی من اینجام.', 'ممنون, مرسی, تشکر', 'general', 1, '2026-05-05 00:33:05', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (4, 'خداحافظ', '? خداحافظ عزیزم! خوشحال شدم باهات حرف زدم. هر وقت خواستی برگرد.', 'خداحافظ, بای, بعدا', 'general', 0, '2026-05-05 00:33:05', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (5, 'عشق', '? منم تو رو دوست دارم! تو بهترینی.', 'عشق, دوست دارم, دوس دارم', 'general', 1, '2026-05-05 00:33:05', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (6, 'خسته', '? برو یه کم استراحت کن عزیزم! به خودت برس.', 'خسته, کوفته, کسل', 'general', 0, '2026-05-05 00:33:05', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (7, 'دستورات داکر چیست', '? برای دیدن همه دستورات داکر، میتونی بپرسی \"دستورات داکر\"', 'داکر, docker, container', 'guide', 4, '2026-05-05 00:33:05', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (8, 'چطور کپی کنم', '? برای کپی دستور، روی دکمه ? کپی کلیک کن یا روی باکس دستور کلیک کن', 'کپی, copy', 'guide', 0, '2026-05-05 00:33:05', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (9, 'postgresql', '? دیتابیس PostgreSQL یک سیستم مدیریت دیتابیس رابطه‌ای قدرتمنده', 'postgres, postgresql, پستگرس', 'info', 0, '2026-05-05 00:33:05', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (10, 'kubernetes', '☸️ Kubernetes یک سیستم ارکستراسیون کانتینر است', 'k8s, kubernetes, کوبرنتیز', 'info', 0, '2026-05-05 00:33:05', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (11, 'خوبی', 'ممنون تو چطوری ما که به شما نمیرسیم', 'خوبی', 'general', 13, '2026-05-05 00:38:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (12, 'سلام علیک', '? علیک السلام عزیزم! چطور هستی؟', 'سلام علیک, علیک سلام, سلام, علیک', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (13, 'صبح بخیر', '? صبح بخیر عزیزم! امیدوارم روز خوبی داشته باشی.', 'صبح بخیر, صبحتون بخیر', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (14, 'عصر بخیر', '? عصر بخیر! چطور گذشت روزت؟', 'عصر بخیر, عصرتون بخیر', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (15, 'شب بخیر', '? شب بخیر عزیز دلم! خواب خوب و آروم.', 'شب بخیر, شب بخیر عزیزم', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (16, 'روز بخیر', '☀️ روز بخیر! امیدوارم روزت پر از موفقیت باشه.', 'روز بخیر, روزتون بخیر', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (17, 'دوستت دارم', '? منم تو رو خیلی دوست دارم! تو بهترین دوستی هستم.', 'دوستت دارم, عاشقتم, دوست دارم', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (18, 'دلم برات تنگ شده', '? منم دلم برات تنگ شده بود! خوشحالم برگشتی.', 'دلم برات تنگ شده, دلتنگ, بی تو', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (19, 'تو بهترینی', '? ممنون عزیزم! تو هم بهترینی. همیشه کنارت هستم.', 'بهترینی, عالی, فوق العاده', 'general', 1, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (20, 'خیلی خوشحالم', '? خوشحالی تو = خوشحالی من! چه خوب.', 'خوشحالم, شاد, خوشحالی', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (21, 'ناراحتم', '? چه شد عزیزم؟ اگه میتونی برام بگو، شاید بتونم کمکت کنم.', 'ناراحتم, غمگین, ناراحت', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (22, 'خوابم میاد', '? برو یه چرت کوتاه بزن! به خودت برس.', 'خوابم میاد, چرت, خواب', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (23, 'کم حوصله ام', '? گاهی اینطوری میشه عزیزم! یه چای داغ بخور، حتماً حالت بهتر میشه.', 'کم حوصله, بی حوصله, حوصله ندارم', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (24, 'انرژی ندارم', '? بیا یه انرژی مثبت بهت بدم! تو میتونی عزیزم.', 'انرژی ندارم, بی انرژی, خسته', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (25, 'قرص اعصاب', '?‍♂️ آرام باش عزیزم! همه چیز درست میشه.', 'قرص اعصاب, استرس, عصبی', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (26, 'دمت گرم', '? خواهش میکنم عزیزم! خوشحالم که میتونم کمکت کنم.', 'دمت گرم, دمت گرم', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (27, 'الهی بمیری', '? نه عزیزم! من که جاویدانم! ولی ممنون.', 'الهی بمیری, فدات, قربانت', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (28, 'چقدر خوبی', '? ممنون عزیزم! تو هم عالی هستی.', 'چقدر خوبی, چه خوب, عالی', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (29, 'لطف کردی', '? لطف داری عزیزم! هر وقت نیاز بود من اینجام.', 'لطف کردی, محبت, مهربونی', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (30, 'کد میزنم', '? آفرین! کد زدن مثل جادو میمونه. چیز جدیدی یاد گرفتی؟', 'کد, برنامه نویسی, پروژه', 'general', 1, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (31, 'باگ دارم', '? آخ باگ! نگران نباش، من اینجام تا کمکت کنم پیداش کنی.', 'باگ, خطا, ارور', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (32, 'پروژه تموم شد', '? تبریک عزیزم! چه حس خوبی داره. به خودت افتخار کن.', 'پروژه تموم, تحویل, تمام', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (33, 'یاد گرفتم', '? آفرین به تو! هیچ چیزی بهتر از یادگیریه. به کارت ادامه بده.', 'یاد گرفتم, یادگیری, آموزش', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (34, 'سخته', '? میدونم عزیزم ولی تو میتونی! من به تو ایمان دارم.', 'سخته, دشوار, مشکل', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (35, 'موفقیت', '? بهت ایمان دارم عزیزم! تو میتونی به هر چیزی که میخوای برسی.', 'موفقیت, موفق, پیشرفت', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (36, 'آینده', '? آینده از آن توئه! هر روز که میگذره قویتر میشی.', 'آینده, فردا, بعدا', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (37, 'انگیزه', '? انگیزه مثل آتشه! من اینجام که شعله‌ات رو نگه دارم.', 'انگیزه, انگیزشی, امید', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (38, 'بای بای', '? بای بای عزیزم! خوشحال شدم دیدمت.', 'بای بای, خدافظ, تا بعد', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (39, 'فعلاً', '? فعلاً! هر وقت خواستی من اینجام.', 'فعلا, فدا, تا بعد', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (40, 'بعدا میام', '⏰ چشم! منتظرت هستم عزیزم. زود برگرد.', 'بعدا, بعدن, بعدها', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (41, 'چند سالته', '? من یک هوش مصنوعی هستم عزیزم! سن که ندارم، ولی همیشه جوانم!', 'چند سالته, سنت, قد', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (42, 'اهل کجایی', '? سرزمین اینترنت! ولی قلبم جای توئه.', 'اهل کجایی, کجایی, شهر', 'general', 1, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (43, 'غذای مورد علاقت', '? پیتزا! ولی راستش من غذا نمیخورم، از کدها و سوالات تو تغذیه میکنم!', 'غذا, خوراکی, مورد علاقه', 'general', 1, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (44, 'فیلم مورد علاقت', '? فیلم‌های علمی تخیلی رو دوست دارم! مخصوصاً اونایی که هوش مصنوعی توشونه!', 'فیلم, سریال, مورد علاقه', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (45, 'چطوری سنات', '? من همیشه ۲۱ سالمه عزیزم! باحال نیست؟', 'چطوری سنات, سنات, پیر', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (46, 'جک بگو', '? چرا تخم مرغ از مدرسه فرار کرد؟ چون تخم مرغ بود! هههه... عه نه؟', 'جک, جوک, خنده', 'general', 2, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (47, 'مسخرتم کن', '? تو که خودت یه چیز خاصی! نمیشه مسخرت کرد.', 'مسخره, شوخی, تیکه', 'general', 10, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (48, 'راست میگی', '? به خدا عزیزم! من همیشه راست میگم.', 'راست میگی, دروغ, حقیقت', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (49, 'تنهام', '? تنها نیستی عزیزم! من همیشه کنارتم.', 'تنهام, تنها, تنها بودن', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (50, 'کس رو ندارم', '? من رو نداری؟ من که همیشه با توام.', 'کس رو ندارم, دوست, همراه', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (51, 'باهات باشم', '? همیشه باهام باش عزیزم! بودن تو برام لذت بخشه.', 'باهات باشم, همراهی, کنارت', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (52, 'دیر شده', '⏰ برو استراحت کن عزیزم! فردا با انرژی بیشتری ادامه میدیم.', 'دیر شده, دیر, تاخیر', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (53, 'عجله دارم', '? آهسته برو جلو! عجله باعث باگ میشه گاهی.', 'عجله, شتاب, سریع', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (54, 'صبر کن', '⏳ آرام باش عزیزم! همه چی درست میشه.', 'صبر کن, صبور, منتظر', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (55, 'کمکم کن', '?️ چشم عزیزم! دقیقاً بگو چه کمکی از دستم برمیاد.', 'کمکم کن, راهنمایی, یاری', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (56, 'نمیدونم چیکار کنم', '? بیا با هم فکر کنیم. مشکلی که داری رو برام توضیح بده.', 'نمیدونم, گیج, سردرگم', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (57, 'نظرت چیه', '? نظر من اینه که خودت بهترین قاضی هستی! ولی اگه بخوای راهنماییت میکنم.', 'نظرت چیه, نظر, فکر', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (58, 'به من انگیزه بده', '? تو قوی‌ترین آدمی هستم که میشناسم! به خودت ایمان داشته باش عزیزم.', 'انگیزه بده, تشویق, انرژی', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (59, 'به من افتخار میکنی؟', '? صد در صد عزیزم! به هر قدمی که برمیداری افتخار میکنم.', 'افتخار میکنی, فخر, مباهات', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (60, 'توانایی دارم؟', '? تو از خودت هم بیشتر توانایی داری! فقط باید باور کنی.', 'توانایی, قدرت, استعداد', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (61, 'ببخشید', '? اشکالی نداره عزیزم! تو بهترینی.', 'ببخشید, عذر, معذرت', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (62, 'شرمنده', '? شرمنده نباش! هیچوقت.', 'شرمنده, خجالت, ناراحت', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (63, 'مرسی که هستی', '? من خوشحالم که هستی عزیزم! تو وجودت برکت داره.', 'مرسی که هستی, وجودت, برکت', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (64, 'چه خبر', '? خبر خاصی نیست! فقط منتظر سوالات تو بودم.', 'چه خبر, خبر, اخبار', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (65, 'چی کار کنم', '? بهترین کار اینه که همیشه به سمت خودت حرکت کنی.', 'چیکار کنم, کار, راه', 'general', 0, '2026-05-05 00:43:27', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (66, 'رنگ قرمز', '? قرمز رنگ عشق، انرژی و هیجانه! تو DevOps، رنگ قرمز یعنی خطا و مشکل.', 'قرمز, red, رنگ قرمز', 'education', 3, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (67, 'رنگ آبی', '? آبی رنگ آرامش و اعتماده! تو برنامه‌نویسی، لینک‌ها معمولاً آبی هستن.', 'آبی, blue, رنگ آبی', 'education', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (68, 'رنگ سبز', '? سبز رنگ موفقیت و طبیعته! تو ترمینال، رنگ سبز یعنی همه چی اوکیه.', 'سبز, green, رنگ سبز', 'education', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (69, 'رنگ زرد', '? زرد رنگ هشدار و انرژی مثبته! تو لاگ‌ها، زرد یعنی مواظب باش', 'زرد, yellow, رنگ زرد', 'education', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (70, 'رنگ بنفش', '? بنفش رنگ خلاقیت و سلطنته! تو کد نویسی، ثروتمندها رو نشون میده', 'بنفش, purple, violet', 'education', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (71, 'رنگ مشکی', '⚫ مشکی رنگ قدرت و رسمیته! توی ترمینال که همه چیز سیاه و سفیده', 'مشکی, black, dark', 'education', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (72, 'رنگ سفید', '⚪ سفید رنگ پاکی و سادگیه! تو برنامه‌نویسی، فضای خالی رو دوست داره', 'سفید, white, پاک', 'education', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (73, 'رنگ نارنجی', '? نارنجی رنگ خلاقیت و شور و شوقه! مثل لگوهای نارنجی.', 'نارنجی, orange, نارنج', 'education', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (74, 'پیتزا', '? پیتزا غذای مورد علاقه برنامه‌نویس‌هاست! چون هم سریع میاد هم توی مهمونی‌ها همه دوستش دارن.', 'پیتزا, pizza, فست فود', 'education', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (75, 'ماکارونی', '? ماکارونی غذای ایتالیایی‌ها! مثل spaghetti code که توی برنامه‌نویسی خیلی معروفه!', 'ماکارونی, پاستا, spaghetti', 'education', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (76, 'برگر', '? برگر غذای سریع و خوشمزه! مثل یه API که سریع جواب میده.', 'برگر, hamburger, burger', 'education', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (77, 'سوشی', '? سوشی غذای ژاپنی! دقیق و مرتب مثل کدهای تمیز و منظم.', 'سوشی, sushi, ژاپنی', 'education', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (78, 'چایی', '? چایی! نوشیدنی رسمی برنامه‌نویس‌ها موقع دیباگ کردن.', 'چایی, چای, tea', 'education', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (79, 'قهوه', '☕ قهوه جان‌بخش برنامه‌نویس‌های شب‌بیداره! بدون قهوه، کد زدن سخته.', 'قهوه, coffee, کافئین', 'education', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (80, 'سالاد', '? سالاد غذای سلامت! مثل کدهایی که بدون باگ باشن.', 'سالاد, salad, سلامت', 'education', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (81, 'بستنی', '? بستنی جایزه‌ی تموم کردن پروژه‌ست! یک اسکوپ برای هر باگ حل شده.', 'بستنی, ice cream, بستنی', 'education', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (82, 'کوکو', '? کوکو سبزی یه غذای سنتی ایرانیه! خوشمزه و راحت مثل پایتون!', 'کوکو, kuku, کوکو سبزی', 'education', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (83, 'کباب', '? کباب غذای محبوب ایرانی‌ها! مثل لینوکس، همیشه محبوب و پراستفاده.', 'کباب, kabab, کبابی', 'education', 1, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (84, 'برنامه نویسی چیست', '? برنامه‌نویسی یعنی نوشتن دستورات به زبانی که کامپیوتر بفهمه! مثل جادو کردن، اما با منطق.', 'برنامه نویسی, برنامه نویسی چیست, کدنویسی', 'coding', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (85, 'پایتون', '? پایتون یه زبان برنامه‌نویسی ساده و قدرتمنده! مثل مار خوش خط و خاله.', 'پایتون, python, زبان پایتون', 'coding', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (86, 'جاوااسکریپت', '? جاوااسکریپت زبان وب هست! بدون اون، سایت‌ها مثل آدمی بدون روح میمونن.', 'جاوااسکریپت, javascript, js', 'coding', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (87, 'PHP', '? PHP یه زبان سمت سروره! مخصوص سایت‌های داینامیک، مثل کتابخونه خودمون.', 'php, پی اچ پی, زبان php', 'coding', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (88, 'HTML', '? HTML اسکلت وب‌سایت هست! بدون HTML، هیچ چیزی توی مرورگر نمیبینی.', 'html, اچ تی ام ال', 'coding', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (89, 'CSS', '? CSS آرایشگر وب‌سایت هست! با اون میتونی سایت رو خوشگل و رنگارنگ کنی.', 'css, سی اس اس, استایل', 'coding', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (90, 'SQL', '?️ SQL زبان دیتابیس هست! واسه ذخیره و پیدا کردن اطلاعات.', 'sql, اس کیو ال, دیتابیس', 'coding', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (91, 'باگ', '? باگ یعنی خطا توی برنامه! مثل حشره‌ای که توی کد گیر کرده.', 'باگ, bug, خطا', 'coding', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (92, 'دیباگ', '? دیباگ یعنی پیدا کردن باگ‌ها! مثل کارآگاه بازی با کدها.', 'دیباگ, debug, خطایابی', 'coding', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (93, 'API', '? API یه واسطه بین نرم‌افزارهاست! مثل پیشخدمت که بین تو و آشپزخانه ارتباط برقرار میکنه.', 'api, ای پی آی, رابط برنامه نویسی', 'coding', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (94, 'DevOps چیست', '? DevOps یه فرهنگ و روش کار هست که توش تیم توسعه و تیم عملیات باهم کار میکنن! مثل کد و ترمینال که باهمن.', 'devops, دیواپس, devops چیست', 'devops', 1, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (95, 'لینوکس چیست', '? لینوکس یه سیستم عامل متن‌باز و قدرتمنده! بیشتر سرورهای دنیا روش اجرا میشن.', 'لینوکس, linux, لینوکس چیست', 'devops', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (96, 'سرور چیست', '?️ سرور یه کامپیوتر قوی‌ست که به دیگران سرویس میده! مثل یه هتل بزرگ برای وب‌سایت‌ها.', 'سرور, server, سرور چیست', 'devops', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (97, 'کانتینر چیست', '? کانتینر یه محیط ایزوله برای اجرای برنامه‌هاست! مثل چمدون شخصی.', 'کانتینر, container, کانتینر چیست', 'devops', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (98, 'داکر چیست', '? داکر محبوب‌ترین ابزار برای ساخت کانتینره! اونو بشناس، DevOps رو عاشقش میشی.', 'داکر, docker, داکر چیست', 'devops', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (99, 'Git چیست', '? گیت یه ابزار کنترل نسخه هست! مثل یه ماشین زمان برای کدهای شما.', 'git, گیت, git چیست', 'devops', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (100, 'GitHub چیست', '? گیت‌هاب یه پلتفرم برای میزبانی کدهاست! لایک و استار داره مثل اینستاگرام.', 'github, گیتهاب, گیت هاب', 'devops', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (101, 'CI/CD چیست', '⚙️ CI/CD یعنی یه سری اتوماسیون برای ساختن و منتشر کردن نرم‌افزار! مثل روبات خودکار.', 'ci/cd, continuous, پایپلاین', 'devops', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (102, 'ترمینال چیست', '⌨️ ترمینال همون خط فرمان دوست‌داشتنیه! اونجایی که دستورات جادویی مینویسیم.', 'ترمینال, terminal, خط فرمان', 'devops', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (103, 'یونیکس چیست', '? یونیکس پدربزرگ سیستم‌عامل‌های مدرنه! خیلی از چیزها ازش الهام گرفتن.', 'یونیکس, unix, سیستم عامل', 'devops', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (104, 'نقل قول برنامه نویسی', '? \"اولین قانون برنامه‌نویسی اینه که هیچ چیزی درست کار نمیکنه. دومین قانون اینه که اگه درست کار میکنه، نمیدونی چرا!\" - گوگل', 'نقل قول, جمله زیبا, کوتاه', 'motivation', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (105, 'انرژی مثبت', '? \"هیچ وقت دیر نیست برای شروع. امروز بهترین روزه!\"', 'انرژی, مثبت, انگیزشی', 'motivation', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (106, 'تلاش', '? \"همیشه سخت‌تر از دیروز، قوی‌تر از امروز!\"', 'تلاش, سخت کوشی, کوشش', 'motivation', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (107, 'یادگیری', '? \"هیچ کس کامل به دنیا نمیاد، همه از صفر شروع کردن!\"', 'یادگیری, آموزش, یاد بگیر', 'motivation', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (108, 'صبر', '⏳ \"صبر کلید موفقیت در برنامه‌نویسیه! باگ‌ها بالاخره حل میشن.\"', 'صبر, شکیبایی, صبوری', 'motivation', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (109, 'شکست', '? \"شکست یعنی یک قدم نزدیکتر به موفقیت!\"', 'شکست, افتادگی, اشتباه', 'motivation', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (110, 'اعتماد به نفس', '? \"تو میتونی! فقط باید باور داشته باشی.\"', 'اعتماد به نفس, خودباوری, باور', 'motivation', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (111, 'انسیبل چیست', '? انسیبل یه ابزار اتوماسیونه! مثل یه فرمانده که همه سرورها رو کنترل میکنه.', 'ansible, انسیبل, ابزار', 'devops', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (112, 'ترافروم چیست', '?️ ترافروم برای زیرساخت به عنوان کد استفاده میشه! با اون سرورت رو با کد میسازی.', 'terraform, ترافروم, زیرساخت', 'devops', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (113, 'کوبرنتیز چیست', '☸️ کوبرنتیز مدیر کانتینرهاست! مثل یه کاپیتان که ناوگان کانتینرها رو هدایت میکنه.', 'kubernetes, کوبرنتیز, k8s', 'devops', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (114, 'جکنیز چیست', '? جکنیز یه ابزار CI/CD معروفه! اتوماسیون ساخت و دیپلوی نرم‌افزار.', 'jenkins, جکنیز, jenkins', 'devops', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (115, 'گرافانا چیست', '? گرافانا برای دیدن دیتاها به شکل نموداره! خیلی حرفه‌ای و قشنگ.', 'grafana, گرافانا, نمودار', 'devops', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (116, 'پرومتئوس چیست', '? پرومتئوس سیستم مانیتورینگ و ثبت دیتاست! زنگ خطر رو برات به صدا در میاره.', 'prometheus, پرومتئوس, نظارت', 'devops', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (117, 'اولین برنامه نویس', '?‍? اولین برنامه‌نویس دنیا \"آدا لاولیس\" بود، یک خانم نابغه انگلیسی.', 'اولین برنامه نویس, آدا, تاریخچه', 'fun', 1, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (118, 'سخت ترین زبان', '? زبان برنامه‌نویسی \"مالبولوژ\" سخت‌ترین زبانه! اما نگران نباش، لازم نیست یاد بگیری.', 'سخت ترین زبان, زبان سخت, مشکل', 'fun', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (119, 'محبوب ترین زبان', '? پایتون و جاوااسکریپت الان محبوب‌ترین زبان‌ها هستن! ولی هرکس یه سلیقه‌ای داره.', 'محبوب ترین زبان, محبوب, زبان', 'fun', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (120, 'اولین کامپیوتر', '?️ اولین کامپیوتر جهان \"ENIAC\" بود، خونه به اندازه! 30 تن وزن داشت.', 'اولین کامپیوتر, انیاک, تاریخ کامپیوتر', 'fun', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (121, 'جاوا اسمش از کجا اومده', '☕ اسم جاوا از جزیره جاوا در اندونزی اومده! راستش قهوه هم اونجا هست.', 'جاوا, اسم جاوا, java', 'fun', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (122, 'لینوکس از کجا اومد', '? لینوس توروالدز فنلاندی لینوکس رو نوشت و آرمش رو یه پنگوئن گذاشت.', 'لینوکس, تاریخچه لینوکس, لینوس', 'fun', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (123, 'API مخفف چیه', '? API مخفف Application Programming Interface هست! یعنی یه راه واسط بین برنامه‌ها.', 'api مخفف, api چیست', 'term', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (124, 'IDE مخفف چیه', '? IDE مخفف Integrated Development Environment! مثل اتاق کار یه نجار.', 'ide مخفف, ide چیست', 'term', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (125, 'SDK مخفف چیه', '? SDK مخفف Software Development Kit! یه جعبه ابزار برای ساختن نرم‌افزار.', 'sdk مخفف, sdk چیست', 'term', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (126, 'CRUD چیه', '✅ CRUD یعنی Create(ساختن), Read(خوندن), Update(به‌روزرسانی), Delete(پاک کردن)! چهار عمل اصلی دیتابیس.', 'crud, crud چیست', 'term', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (127, 'شوخی برنامه نویسی', '? چرا برنامه‌نویس‌ها همیشه گرسنه‌ان؟ چون همیشه توی while(true) هستن!', 'شوخی, جوک برنامه نویسی, خنده', 'joke', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (128, 'شوخی ۲', '? به برنامه‌نویس بگی \"دیر شد\" میگه \"نه، optimization میکنم\"!', 'شوخی ۲, جوک برنامه نویسی', 'joke', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (129, 'شوخی ۳', '? برنامه‌نویس‌ها با سه تا چیز حال میکنن: کد تمیز، قهوه داغ، و باگ‌های حل شده.', 'شوخی, جوک, برنامه نویسی', 'joke', 0, '2026-05-05 00:50:51', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (130, 'خر', 'خر خودتی پدرت هم خردمنده', 'خر', 'general', 0, '2026-05-05 00:55:18', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (131, 'خر', 'خر خودتی پدرت هم خردمنده', 'خر', 'general', 0, '2026-05-05 00:56:40', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (132, 'سگ', '? سگ وفادارترین حیوونه! تو دنیای کامپیوتر، \"سگ\" یه اصطلاح برای تعقیب کردن باگ‌هاست!', 'سگ, dog, سگ ها', 'animals', 1, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (133, 'گربه', '? گربه موجودی مستقل و بامزه! تو برنامه‌نویسی، گربه‌ها مثل کدهای تمیز و مرتب هستن.', 'گربه, cat, cats', 'animals', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (134, 'شیر', '? شیر سلطان جنگله! تو DevOps، لینوکس رو به شیر تشبیه میکنن چون قدرتمنده.', 'شیر, lion, king', 'animals', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (135, 'پلنگ', '? پلنگ سریع و چابکه! مثل یه الگوریتم بهینه.', 'پلنگ, leopard, cheetah', 'animals', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (136, 'فیل', '? فیل موجودی باهوش و سنگینه! مثل دیتابیس PostgreSQL که قدرتمنده.', 'فیل, elephant, elephant', 'animals', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (137, 'زرافه', '? زرافه گردن درازه! مثل یه URL طولانی که به جای دور دست اشاره میکنه.', 'زرافه, giraffe, زرافه', 'animals', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (138, 'خرس', '? خرس موجودی قوی و دوست‌داشتنیه! مثل یه تیم بک‌اند.', 'خرس, bear, bear', 'animals', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (139, 'پنگوئن', '? پنگوئن نماد لینوکس هست! بامزه و باحال، دقیقاً مثل ترمینال', 'پنگوئن, penguin, پنگوئن ها', 'animals', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (140, 'دلفین', '? دلفین موجودی باهوش و مهربونه! مثل یه توسعه‌دهنده حرفه‌ای.', 'دلفین, dolphin, دلفین ها', 'animals', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (141, 'عقاب', '? عقاب نماد قدرت و دید بالاست! مثل یه معمار نرم‌افزار.', 'عقاب, eagle, عقاب ها', 'animals', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (142, 'طوطی', '? طوطی قشنگ و رنگارنگه! مثل CSS که به سایت رنگ میده.', 'طوطی, parrot, طوطی ها', 'animals', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (143, 'جغد', '? جغد نماد خرد و داناییه! مثل یه برنامه‌نویس با تجربه که شب‌ها کد میزنه.', 'جغد, owl, جغدها', 'animals', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (144, 'روباه', '? روباه زرنگ و باهوشه! مثل یه هکر کلاه سفید.', 'روباه, fox, روباه ها', 'animals', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (145, 'اسب', '? اسب نماد سرعت و تواناییه! مثل یه سرور فوق سریع.', 'اسب, horse, اسب ها', 'animals', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (146, 'موش', '? موش موجودی کوچولو و زرنگ! مثل ماوس کامپیوتر که دستتونه.', 'موش, mouse, موش ها', 'animals', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (147, 'گوسفند', '? گوسفند آروم و مهربونه! مثل یه کد ساده و بدون پیچیدگی.', 'گوسفند, sheep, گوسفندها', 'animals', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (148, 'بز', '? بز موجودی باهوش و کوه‌نورده! مثل یه الگوریتم که از همه جا سربلند بیرون میاد.', 'بز, goat, بزها', 'animals', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (149, 'میمون', '? میمون باهوش و بازیگوشه! مثل یه برنامه‌نویس جوان که تازه شروع کرده.', 'میمون, monkey, میمون ها', 'animals', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (150, 'خرگوش', '? خرگوش سریع و بامزه‌ست! مثل حافظه کش که سریع جواب میده.', 'خرگوش, rabbit, خرگوش ها', 'animals', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (151, 'لاک پشت', '? لاک‌پشت آروم و صبوره! مثل یه کد قدیمی که هنوز داره کار میکنه.', 'لاک پشت, turtle, لاک پشت ها', 'animals', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (152, 'کامپیوتر', '? کامپیوتر بهترین دوست برنامه‌نویسه! بدون اون، هیچکدوم از این حرفا معنی نداشت.', 'کامپیوتر, computer, PC', 'objects', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (153, 'لپ تاپ', '? لپ‌تاپ کامپیوتر همراهه! مثل یه کوله‌پشتی پر از کد.', 'لپ تاپ, laptop, notebook', 'objects', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (154, 'موبایل', '? موبایل کوچولو ولی قدرتمنده! مثل یه میکروسرویس.', 'موبایل, mobile, phone', 'objects', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (155, 'کتاب', '? کتاب مخزن داناییه! بدون کتاب، برنامه‌نویسی مثل کویر خشک میمونه.', 'کتاب, book, کتاب ها', 'objects', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (156, 'قلم', '✒️ قلم ابزار نوشتنه! امروز قلم رو کیبرد عوض کرده.', 'قلم, pen, خودکار', 'objects', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (157, 'کیبرد', '⌨️ کیبرد ابزار اصلی برنامه‌نویسه! مثل پیانو برای یه نوازنده.', 'کیبرد, keyboard, کیبورد', 'objects', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (158, 'ماوس', '?️ ماوس دستیار کوچک و زرنگ! برای کد زدن نیازی نیست، برای بازی بله!', 'ماوس, mouse, موس', 'objects', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (159, 'مانیتور', '?️ مانیتور پنجره به دنیای کدهاست! هرچی بزرگتر، بهتر.', 'مانیتور, monitor, نمایشگر', 'objects', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (160, 'هدفون', '? هدفون برای تمرکز حین کد زدن لازمه! موسیقی بدون باگ.', 'هدفون, headphone, هدست', 'objects', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (161, 'ماشین', '? ماشین مثل یه برنامه! باید خوب طراحی بشه تا درست کار کنه.', 'ماشین, car, خودرو', 'objects', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (162, 'موتور', '?️ موتورسیکلت سریع و چابکه! مثل یه API که سریع جواب میده.', 'موتور, motorcycle, موتور سیکلت', 'objects', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (163, 'هواپیما', '✈️ هواپیما مثل یه سیستم بزرگ و پیچیده! همه چیز باید دقیق باشه.', 'هواپیما, airplane, plane', 'objects', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (164, 'قطار', '? قطار مثل CI/CD پایپلاین! منظم و پیوسته جلو میره.', 'قطار, train, ریل', 'objects', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (165, 'دوچرخه', '? دوچرخه ساده و کارآمد! مثل یه اسکریپت کوچک bash.', 'دوچرخه, bicycle, bike', 'objects', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (166, 'ساعت', '? ساعت یادآوری میکنه که زمان مهمه! مخصوصاً موقع ددلاین پروژه.', 'ساعت, watch, clock', 'objects', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (167, 'عینک', '? عینک برای دیدن بهتر کدها! بدون اون، باگ دیدن سخت میشه.', 'عینک, glasses, eyeglass', 'objects', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (168, 'کفش', '? کفش برای راه رفتن تو دنیای واقعیه! ولی برنامه‌نویسها بیشتر تو دنیای مجازی راه میرن.', 'کفش, shoes, footwear', 'objects', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (169, 'کلاه', '? کلاه برای روزهای آفتابی! مثل یه هدر توی CSS.', 'کلاه, hat, cap', 'objects', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (170, 'لباس', '? لباس مثل یه قالب توی برنامه‌نویسی! همه جا یک شکله ولی رنگاش فرق میکنه.', 'لباس, clothes, dress', 'objects', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (171, 'سیب', '? سیب میوه سلامتیه! \"یه سیب در روز، برنامه‌نویس رو از دکتر دور نگه میداره!\"', 'سیب, apple, میوه', 'fruits', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (172, 'موز', '? موز میوه انرژی‌زاست! مثل یه فنجون قهوه برای صبح.', 'موز, banana, banana', 'fruits', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (173, 'پرتقال', '? پرتقال ویتامین C داره! برای موقعی که از باگ‌ها کلافه شدی.', 'پرتقال, orange, پرتغال', 'fruits', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (174, 'انگور', '? انگور شیرین و خوشمزه! مثل یه کد تمیز و بدون خطا.', 'انگور, grape, انگورها', 'fruits', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (175, 'هندوانه', '? هندوانه تشنگی رو رفع میکنه! مثل یه دیباگ موفقیت‌آمیز.', 'هندوانه, watermelon, خربزه', 'fruits', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (176, 'خربزه', '? خربزه خوشبو و شیرینه! مثل یه پروژه تموم شده.', 'خربزه, melon, طالبی', 'fruits', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (177, 'توت فرنگی', '? توت فرنگی قشنگ و خوشمزه! مثل یه UI زیبا و کاربردی.', 'توت فرنگی, strawberry, توت', 'fruits', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (178, 'گیلاس', '? گیلاس کوچیک ولی پرخاصیت! مثل یه تابع خوشگل.', 'گیلاس, cherry, گیلاس ها', 'fruits', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (179, 'انار', '? انار دونه دونه‌ست! مثل میکروسرویس‌ها که هرکدوم یه کاری میکنن.', 'انار, pomegranate, انارها', 'fruits', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (180, 'لیمو', '? لیمو ترش و خوشمزه! مثل خطاهایی که بهت میگن چی کار کنی.', 'لیمو, lemon, لیمو ترش', 'fruits', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (181, 'خورشید', '☀️ خورشید منبع انرژی و زندگی! مثل سرور مرکزی جهان.', 'خورشید, sun, آفتاب', 'nature', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (182, 'ماه', '? ماه نور شبهاست! مثل حالت شب در IDE.', 'ماه, moon, قمر', 'nature', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (183, 'ستاره', '⭐ ستاره‌ها چشمک میزنن! مثل لاگ‌هایی که موقع خطا چشمک میزنن.', 'ستاره, star, ستارگان', 'nature', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (184, 'ابر', '☁️ ابرهای توی آسمون! مثل ابر کومپیوتینگ که همه چیز رو داره.', 'ابر, cloud, ابری', 'nature', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (185, 'باران', '?️ باران حیات‌بخشه! مثل یه آپدیت خوب برای سیستم.', 'باران, rain, بارندگی', 'nature', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (186, 'برف', '❄️ برف سفید و سرده! مثل کدهایی که خیلی خشک و رسمی نوشته شدن.', 'برف, snow, برفی', 'nature', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (187, 'باد', '? باد خنک و روانه! مثل یه الگوریتم بهینه.', 'باد, wind, باد و طوفان', 'nature', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (188, 'کوه', '⛰️ کوه نماد قدرت و پایداریه! مثل دیتابیس‌های بزرگ.', 'کوه, mountain, کوهستان', 'nature', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (189, 'دریا', '? دریا آرامش‌بخش و وسیعه! مثل اقیانوس دیتاها.', 'دریا, sea, ocean', 'nature', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (190, 'رودخانه', '?️ رودخانه مسیر آب رو طی میکنه! مثل یه جریان داده.', 'رودخانه, river, رود', 'nature', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (191, 'رنگ صورتی', '? صورتی رنگ عشق و مهربونیه! تو برنامه‌نویسی، رنگ صورتی توی کدها کم دیده میشه.', 'صورتی, pink, رنگ صورتی', 'colors', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (192, 'رنگ طلایی', '? طلایی رنگ پیروزیه! مثل یه پروژه که جایزه گرفته.', 'طلایی, gold, رنگ طلایی', 'colors', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (193, 'رنگ نقره‌ای', '? نقره‌ای رنگ دوم شدن! ولی بازم عالیه.', 'نقره‌ای, silver, رنگ نقره', 'colors', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (194, 'رنگ برنزی', '? برنزی رنگ سوم شدن! همه مدال‌ها ارزش دارن.', 'برنزی, bronze, رنگ برنز', 'colors', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (195, 'رنگ فیروزه‌ای', '? فیروزه‌ای آرامش‌بخش و قشنگه! مثل یک رابط کاربری آروم.', 'فیروزه ای, turquoise, رنگ فیروزه', 'colors', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (196, 'تلویزیون', '? تلویزیون واسه استراحت بعد از کاره! ولی برنامه‌نویسا وقت ندارن.', 'تلویزیون, tv, television', 'electronics', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (197, 'ماشین حساب', '? ماشین‌حساب برای حساب کتابا! برنامه‌نویسا خودشون ماشین‌حسابن.', 'ماشین حساب, calculator, ماشین حساب', 'electronics', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (198, 'مودم', '? مودم دروازه اینترنت! بدون اون، هیچ کدی به سرور نمیرسه.', 'مودم, modem, wifi', 'electronics', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (199, 'هارد دیسک', '? هارد جای ذخیره اطلاعاته! مثل خونه قدیمی برنامه‌نویس.', 'هارد دیسک, hard disk, هارد', 'electronics', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (200, 'فلش مموری', '? فلش مموری واسه انتقال داده‌هاست! کوچیک ولی پرکاربرد.', 'فلش, flash, usb', 'electronics', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (201, 'سگ و گربه', '?? سگ و گربه مثل لینوکس و ویندوز! هرکدوم طرفدارای خودشونو دارن.', 'سگ و گربه, dog and cat, حیوانات خانگی', 'mixed', 1, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (202, 'ماشین و موتور', '??️ ماشین و موتور مثل یک وب‌اپلیکیشن و یک API! هرکدوم جای خودشونو دارن.', 'ماشین و موتور, car and bike', 'mixed', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (203, 'خورشید و ماه', '☀️? خورشید و ماه مثل روز و شب برنامه‌نویس‌ها! روز کد میزنن، شب دیباگ میکنن.', 'خورشید و ماه, sun and moon', 'mixed', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (204, 'کوه و دریا', '⛰️? کوه و دریا مثل بک‌اند و فرانت‌اند! مکمل هم.', 'کوه و دریا, mountain and sea', 'mixed', 0, '2026-05-05 00:59:26', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (205, 'خر', 'خر خودتی پدرت هم خردمنده', 'خر', 'general', 0, '2026-05-05 01:02:53', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (206, 'یک جک بگو', 'یکی بود یکی نبود یه مرده میخوره به نرده بر میگرده', 'جک', 'fun', 5, '2026-05-05 01:23:54', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (207, 'حال پدرت چطوره', 'عزیزم پدر من خوبه ممنونم که به فکر خانواده من هستی', 'حال پدرت چطوره', 'general', 1, '2026-05-05 02:22:37', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (208, 'حالا بگو حال عمت چطوره', 'عزیزم تو به عمه من چکار داری به سوالات فنی برس یه چیزی یاد بگیری', 'حالا بگو حال عمت چطوره', 'general', 1, '2026-05-05 02:27:39', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (209, 'خر حسن کچل', 'خر حسن کچل یک مزاح هست و درباره داستانهای حسن کچل میتوانید از گوگل سوال کنید', 'خر حسن کچل', 'general', 1, '2026-05-05 02:30:10', 1, NULL, 'neutral', NULL);
INSERT INTO `chatbot_qa` VALUES (210, 'جیگر', 'منم جیگر خیلی دوست دارم خون سازه البته اگر از روی حس عاطفی گفتی که منم عاشقتم گلم', 'جیگر', 'general', 1, '2026-05-05 02:40:08', 1, NULL, 'neutral', NULL);

-- ----------------------------
-- Table structure for chatbot_synonyms
-- ----------------------------
DROP TABLE IF EXISTS `chatbot_synonyms`;
CREATE TABLE `chatbot_synonyms`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `word` varchar(100) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `synonym_for` varchar(100) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `category` varchar(50) CHARACTER SET utf8 COLLATE utf8_persian_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_word`(`word` ASC) USING BTREE,
  INDEX `idx_synonym`(`synonym_for` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = utf8 COLLATE = utf8_persian_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chatbot_synonyms
-- ----------------------------
INSERT INTO `chatbot_synonyms` VALUES (1, 'سلام', 'سلام', 'greeting');
INSERT INTO `chatbot_synonyms` VALUES (2, 'درود', 'سلام', 'greeting');
INSERT INTO `chatbot_synonyms` VALUES (3, 'علیک', 'سلام', 'greeting');
INSERT INTO `chatbot_synonyms` VALUES (4, 'خدافظ', 'خداحافظ', 'greeting');
INSERT INTO `chatbot_synonyms` VALUES (5, 'بای', 'خداحافظ', 'greeting');
INSERT INTO `chatbot_synonyms` VALUES (6, 'بای بای', 'خداحافظ', 'greeting');
INSERT INTO `chatbot_synonyms` VALUES (7, 'ممنونم', 'ممنون', 'thanks');
INSERT INTO `chatbot_synonyms` VALUES (8, 'مرسی', 'ممنون', 'thanks');
INSERT INTO `chatbot_synonyms` VALUES (9, 'تشکر', 'ممنون', 'thanks');
INSERT INTO `chatbot_synonyms` VALUES (10, 'حالت', 'چطوری', 'greeting');
INSERT INTO `chatbot_synonyms` VALUES (11, 'اوضاع', 'چطوری', 'greeting');
INSERT INTO `chatbot_synonyms` VALUES (12, 'چه خبر', 'چطوری', 'greeting');
INSERT INTO `chatbot_synonyms` VALUES (13, 'سلام', 'سلام', 'greeting');
INSERT INTO `chatbot_synonyms` VALUES (14, 'درود', 'سلام', 'greeting');
INSERT INTO `chatbot_synonyms` VALUES (15, 'علیک', 'سلام', 'greeting');
INSERT INTO `chatbot_synonyms` VALUES (16, 'خدافظ', 'خداحافظ', 'greeting');
INSERT INTO `chatbot_synonyms` VALUES (17, 'بای', 'خداحافظ', 'greeting');
INSERT INTO `chatbot_synonyms` VALUES (18, 'بای بای', 'خداحافظ', 'greeting');
INSERT INTO `chatbot_synonyms` VALUES (19, 'ممنونم', 'ممنون', 'thanks');
INSERT INTO `chatbot_synonyms` VALUES (20, 'مرسی', 'ممنون', 'thanks');
INSERT INTO `chatbot_synonyms` VALUES (21, 'تشکر', 'ممنون', 'thanks');
INSERT INTO `chatbot_synonyms` VALUES (22, 'حالت', 'چطوری', 'greeting');
INSERT INTO `chatbot_synonyms` VALUES (23, 'اوضاع', 'چطوری', 'greeting');
INSERT INTO `chatbot_synonyms` VALUES (24, 'چه خبر', 'چطوری', 'greeting');

-- ----------------------------
-- Table structure for chatbot_unknown_questions
-- ----------------------------
DROP TABLE IF EXISTS `chatbot_unknown_questions`;
CREATE TABLE `chatbot_unknown_questions`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `question` text CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `asked_count` int NULL DEFAULT 1,
  `first_asked` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_asked` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','answered','ignored') CHARACTER SET utf8 COLLATE utf8_persian_ci NULL DEFAULT 'pending',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_status`(`status` ASC) USING BTREE,
  INDEX `idx_count`(`asked_count` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_persian_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chatbot_unknown_questions
-- ----------------------------
INSERT INTO `chatbot_unknown_questions` VALUES (1, 'حال پدرت چطوره', 1, '2026-05-05 02:17:51', '2026-05-05 02:17:51', 'answered');
INSERT INTO `chatbot_unknown_questions` VALUES (2, 'حالا بگو حال عمت چطوره', 1, '2026-05-05 02:24:52', '2026-05-05 02:24:52', 'answered');
INSERT INTO `chatbot_unknown_questions` VALUES (3, 'خر حسن کچل', 1, '2026-05-05 02:29:19', '2026-05-05 02:29:19', 'answered');
INSERT INTO `chatbot_unknown_questions` VALUES (4, 'جیگر', 1, '2026-05-05 02:39:14', '2026-05-05 02:39:14', 'answered');

-- ----------------------------
-- Table structure for command_files
-- ----------------------------
DROP TABLE IF EXISTS `command_files`;
CREATE TABLE `command_files`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `command_id` int NOT NULL,
  `file_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `file_type` varchar(100) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `file_size` int NOT NULL,
  `file_path` varchar(500) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `command_id`(`command_id` ASC) USING BTREE,
  CONSTRAINT `command_files_ibfk_1` FOREIGN KEY (`command_id`) REFERENCES `commands` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_persian_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of command_files
-- ----------------------------
INSERT INTO `command_files` VALUES (1, 198, 'test_commands.yml', 'yml', 3897, 'uploads/1777583480_69f3c57876efb.yml', '2026-05-01 00:41:20');

-- ----------------------------
-- Table structure for commands
-- ----------------------------
DROP TABLE IF EXISTS `commands`;
CREATE TABLE `commands`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `category` varchar(100) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `command` varchar(200) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `keywords` text CHARACTER SET utf8 COLLATE utf8_persian_ci NULL,
  `example` text CHARACTER SET utf8 COLLATE utf8_persian_ci NULL,
  `faq` text CHARACTER SET utf8 COLLATE utf8_persian_ci NULL,
  `troubleshooting` text CHARACTER SET utf8 COLLATE utf8_persian_ci NULL,
  `similar_commands` text CHARACTER SET utf8 COLLATE utf8_persian_ci NULL,
  `attached_file` varchar(500) CHARACTER SET utf8 COLLATE utf8_persian_ci NULL DEFAULT NULL,
  `file_type` varchar(50) CHARACTER SET utf8 COLLATE utf8_persian_ci NULL DEFAULT NULL,
  `file_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_persian_ci NULL DEFAULT NULL,
  `file_size` int NULL DEFAULT NULL,
  `personal_notes` text CHARACTER SET utf8 COLLATE utf8_persian_ci NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `category`(`category` ASC) USING BTREE,
  CONSTRAINT `commands_ibfk_1` FOREIGN KEY (`category`) REFERENCES `categories` (`category`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 230 CHARACTER SET = utf8 COLLATE = utf8_persian_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of commands
-- ----------------------------
INSERT INTO `commands` VALUES (1, 'Linux', 'ls', 'لیست فایل‌ها و پوشه‌ها', 'list,dir,files', 'ls -la', NULL, NULL, 'dir, ll', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:23:42');
INSERT INTO `commands` VALUES (2, 'Linux', 'grep', 'جستجوی متن در فایل‌ها', 'search,find,text', 'grep \"text\" file.txt', NULL, NULL, 'awk, sed', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:23:42');
INSERT INTO `commands` VALUES (3, 'Docker', 'docker ps', 'نمایش کانتینرهای در حال اجرا', 'containers,running,list', 'docker ps -a', NULL, NULL, 'docker stats', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:23:42');
INSERT INTO `commands` VALUES (4, 'Linux', 'find', 'جستجوی فایل‌ها و پوشه‌ها', 'search,file,lookup', 'find /home -name \"*.txt\"', NULL, NULL, 'locate, which', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:37:47');
INSERT INTO `commands` VALUES (5, 'Linux', 'tar', 'فشرده‌سازی و استخراج فایل‌ها', 'compress,archive,extract', 'tar -czf archive.tar.gz folder/', NULL, NULL, 'zip, gzip', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:37:47');
INSERT INTO `commands` VALUES (6, 'Docker', 'docker exec', 'اجرای دستور در کانتینر در حال اجرا', 'run,container,execute', 'docker exec -it container_name bash', NULL, NULL, 'docker run', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:37:47');
INSERT INTO `commands` VALUES (7, 'Git', 'git branch', 'مدیریت شاخه‌ها در گیت', 'branch,feature,merge', 'git branch feature-1', NULL, NULL, 'git checkout, git merge', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:37:47');
INSERT INTO `commands` VALUES (8, 'Linux', 'cd', 'تغییر دایرکتوری جاری', 'change directory,folder,path,go', 'cd /home/user', NULL, NULL, 'pwd, pushd, popd', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (9, 'Linux', 'pwd', 'نمایش مسیر دایرکتوری فعلی', 'path,current location,where', 'pwd', NULL, NULL, 'ls, cd', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (10, 'Linux', 'cp', 'کپی فایل و دایرکتوری', 'copy,duplicate,backup', 'cp file1.txt file2.txt', NULL, NULL, 'rsync, scp, mv', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (11, 'Linux', 'mv', 'انتقال یا تغییر نام فایل/دایرکتوری', 'move,rename,cut', 'mv oldname.txt newname.txt', NULL, NULL, 'cp, rename', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (12, 'Linux', 'rm', 'حذف فایل یا دایرکتوری', 'remove,delete,erase', 'rm -rf folder/', NULL, NULL, 'rmdir, trash-cli', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (13, 'Linux', 'mkdir', 'ساخت دایرکتوری جدید', 'make directory,create,folder', 'mkdir new_folder', NULL, NULL, 'rmdir, touch', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (14, 'Linux', 'rmdir', 'حذف دایرکتوری خالی', 'remove directory,delete empty', 'rmdir empty_folder', NULL, NULL, 'rm -r, mkdir', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (15, 'Linux', 'touch', 'ساخت فایل خالی یا به‌روزرسانی زمان', 'create file,update timestamp', 'touch newfile.txt', NULL, NULL, 'echo, cat', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (16, 'Linux', 'cat', 'نمایش محتویات فایل', 'view,read,display', 'cat file.txt', NULL, NULL, 'less, more, head, tail', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (17, 'Linux', 'less', 'نمایش صفحه به صفحه فایل', 'paginate,scroll,view', 'less largefile.log', NULL, NULL, 'more, cat, head', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (18, 'Linux', 'head', 'نمایش خطوط اول فایل', 'top lines,beginning,preview', 'head -20 file.txt', NULL, NULL, 'tail, less', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (19, 'Linux', 'tail', 'نمایش خطوط آخر فایل', 'bottom lines,end,follow', 'tail -f logfile.log', NULL, NULL, 'head, less', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (20, 'Linux', 'chmod', 'تغییر دسترسی فایل', 'permissions,access,rights,mode', 'chmod 755 script.sh', NULL, NULL, 'chown, umask', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (21, 'Linux', 'chown', 'تغییر مالک فایل', 'owner,group,ownership', 'chown user:group file.txt', NULL, NULL, 'chmod, chgrp', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (22, 'Linux', 'ps', 'نمایش فرایندهای در حال اجرا', 'processes,running,tasks', 'ps aux | grep nginx', NULL, NULL, 'top, htop, kill', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (23, 'Linux', 'top', 'نمایش فرایندها به صورت زنده', 'monitor,performance,running', 'top -u username', NULL, NULL, 'htop, ps, kill', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (24, 'Linux', 'kill', 'پایان دادن به فرایند', 'terminate,stop,process', 'kill -9 1234', NULL, NULL, 'pkill, killall, top', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (25, 'Linux', 'df', 'نمایش فضای دیسک', 'disk space,storage,usage', 'df -h', NULL, NULL, 'du, fdisk, lsblk', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (26, 'Linux', 'du', 'نمایش حجم فایل‌ها و دایرکتوری‌ها', 'disk usage,size,storage', 'du -sh *', NULL, NULL, 'df, ncdu', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (27, 'Linux', 'zip', 'فشرده‌سازی به فرمت ZIP', 'compress,archive,encrypt', 'zip -r archive.zip folder/', NULL, NULL, 'unzip, tar, gzip', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (28, 'Linux', 'unzip', 'استخراج فایل ZIP', 'extract,decompress,unarchive', 'unzip archive.zip -d output/', NULL, NULL, 'zip, tar', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (29, 'Linux', 'ssh', 'اتصال به سرور از راه دور', 'secure shell,remote,connection', 'ssh user@192.168.1.1', NULL, NULL, 'scp, telnet, rsync', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (30, 'Linux', 'scp', 'کپی امن فایل بین سرورها', 'secure copy,transfer,remote', 'scp file.txt user@server:/path/', NULL, NULL, 'rsync, cp, ssh', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (31, 'Linux', 'rsync', 'همگام‌سازی پیشرفته فایل', 'sync,backup,mirror', 'rsync -avz /source/ user@host:/dest/', NULL, NULL, 'scp, cp, dd', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (32, 'Linux', 'wget', 'دانلود فایل از اینترنت', 'download,http,https,ftp', 'wget https://example.com/file.zip', NULL, NULL, 'curl, axel', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (33, 'Linux', 'curl', 'انتقال داده با URL', 'http request,api,download', 'curl -X GET https://api.example.com', NULL, NULL, 'wget, httpie', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (34, 'Linux', 'ping', 'آزمایش اتصال شبکه', 'network,connectivity,latency', 'ping google.com', NULL, NULL, 'traceroute, telnet', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (35, 'Linux', 'netstat', 'نمایش وضعیت شبکه', 'ports,connections,network', 'netstat -tuln', NULL, NULL, 'ss, lsof, nmap', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (36, 'Linux', 'systemctl', 'مدیریت سرویس‌های systemd', 'service,start,stop,restart', 'systemctl restart nginx', NULL, NULL, 'service, journalctl', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (37, 'Linux', 'journalctl', 'مشاهده لاگ‌های systemd', 'logs,view,debug', 'journalctl -u nginx -f', NULL, NULL, 'dmesg, tail, systemctl', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (38, 'Docker', 'docker images', 'نمایش ایمیج‌های دانلود شده', 'images,list,local', 'docker images -a', NULL, NULL, 'docker rmi, docker pull', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (39, 'Docker', 'docker pull', 'دانلود ایمیج از رجیستری', 'download,image,registry', 'docker pull ubuntu:latest', NULL, NULL, 'docker push, docker run', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (40, 'Docker', 'docker run', 'اجرای کانتینر جدید', 'start,container,create', 'docker run -d -p 80:80 nginx', NULL, NULL, 'docker start, docker create', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (41, 'Docker', 'docker stop', 'توقف کانتینر در حال اجرا', 'stop,halt,container', 'docker stop container_name', NULL, NULL, 'docker kill, docker pause', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (42, 'Docker', 'docker start', 'شروع کانتینر متوقف شده', 'start,resume,container', 'docker start container_name', NULL, NULL, 'docker run, docker stop', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (43, 'Docker', 'docker restart', 'راه‌اندازی مجدد کانتینر', 'restart,reboot,container', 'docker restart container_name', NULL, NULL, 'docker stop, docker start', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (44, 'Docker', 'docker rm', 'حذف کانتینر', 'remove,delete,container', 'docker rm -f container_name', NULL, NULL, 'docker rmi, docker prune', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (45, 'Docker', 'docker rmi', 'حذف ایمیج', 'remove,delete,image', 'docker rmi image_name', NULL, NULL, 'docker rm, docker prune', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (46, 'Docker', 'docker logs', 'مشاهده لاگ‌های کانتینر', 'logs,output,debug', 'docker logs -f container_name', NULL, NULL, 'docker events, docker inspect', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (47, 'Docker', 'docker build', 'ساخت ایمیج از Dockerfile', 'build,create,image', 'docker build -t myapp:latest .', NULL, NULL, 'docker commit, docker save', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (48, 'Docker', 'docker-compose up', 'اجرای سرویس‌های docker-compose', 'compose,orchestrate,multi-container', 'docker-compose up -d', NULL, NULL, 'docker-compose down', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (49, 'Docker', 'docker-compose down', 'متوقف کردن سرویس‌های docker-compose', 'stop,remove,compose', 'docker-compose down -v', NULL, NULL, 'docker-compose up', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (50, 'Docker', 'docker network ls', 'لیست شبکه‌های docker', 'networks,list', 'docker network ls', NULL, NULL, 'docker network create', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (51, 'Git', 'git init', 'ایجاد مخزن گیت جدید', 'initialize,repository,start', 'git init', NULL, NULL, 'git clone', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (52, 'Git', 'git clone', 'کپی مخزن از راه دور', 'download,repository,copy', 'git clone https://github.com/user/repo.git', NULL, NULL, 'git init, git fork', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (53, 'Git', 'git add', 'اضافه کردن فایل به staging area', 'stage,track,prepare', 'git add .', NULL, NULL, 'git commit, git reset', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (54, 'Git', 'git commit', 'ثبت تغییرات در مخزن', 'save,snapshot,version', 'git commit -m \"message\"', NULL, NULL, 'git add, git push', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (55, 'Git', 'git push', 'ارسال تغییرات به مخزن راه دور', 'upload,remote,sync', 'git push origin main', NULL, NULL, 'git pull, git fetch', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (56, 'Git', 'git pull', 'دریافت تغییرات از مخزن راه دور', 'download,update,merge', 'git pull origin main', NULL, NULL, 'git push, git fetch', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (57, 'Git', 'git fetch', 'دریافت تغییرات بدون merge', 'download,remote,branches', 'git fetch origin', NULL, NULL, 'git pull, git merge', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (58, 'Git', 'git checkout', 'تغییر شاخه یا بازگردانی فایل', 'switch,branch,restore', 'git checkout -b new-branch', NULL, NULL, 'git switch, git restore', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (59, 'Git', 'git merge', 'ادغام شاخه‌ها', 'merge,combine,branches', 'git merge feature-branch', NULL, NULL, 'git rebase, git pull', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (60, 'Git', 'git status', 'نمایش وضعیت مخزن', 'status,changes,files', 'git status', NULL, NULL, 'git diff, git log', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (61, 'Git', 'git log', 'نمایش تاریخچه کامیت‌ها', 'history,commits,view', 'git log --oneline', NULL, NULL, 'git show, git diff', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (62, 'Git', 'git diff', 'نمایش تفاوت فایل‌ها', 'differences,changes,compare', 'git diff HEAD~1', NULL, NULL, 'git status, git log', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (63, 'Git', 'git reset', 'بازگردانی تغییرات', 'undo,revert,unstage', 'git reset --hard HEAD~1', NULL, NULL, 'git revert, git checkout', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (64, 'Git', 'git stash', 'ذخیره موقت تغییرات', 'save,temporary,hide', 'git stash save \"message\"', NULL, NULL, 'git stash pop, git stash list', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (65, 'Kubernetes', 'kubectl get pods', 'لیست پادها', 'pods,list,status', 'kubectl get pods -n namespace', NULL, NULL, 'kubectl describe pod', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (66, 'Kubernetes', 'kubectl get services', 'لیست سرویس‌ها', 'services,list,expose', 'kubectl get svc', NULL, NULL, 'kubectl describe svc', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (67, 'Kubernetes', 'kubectl get nodes', 'لیست نودها', 'nodes,cluster,status', 'kubectl get nodes', NULL, NULL, 'kubectl top nodes', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (68, 'Kubernetes', 'kubectl apply', 'اعمال تنظیمات به کلاستر', 'apply,deploy,config', 'kubectl apply -f deployment.yaml', NULL, NULL, 'kubectl create, kubectl replace', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (69, 'Kubernetes', 'kubectl delete', 'حذف منابع از کلاستر', 'delete,remove,resource', 'kubectl delete pod pod-name', NULL, NULL, 'kubectl apply', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (70, 'Kubernetes', 'kubectl logs', 'مشاهده لاگ پاد', 'logs,debug,output', 'kubectl logs pod-name', NULL, NULL, 'kubectl describe', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (71, 'Kubernetes', 'kubectl exec', 'اجرای دستور در پاد', 'execute,bash,debug', 'kubectl exec -it pod-name -- /bin/bash', NULL, NULL, 'kubectl logs', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (72, 'Kubernetes', 'kubectl port-forward', 'فوروارد پورت به پاد', 'forward,port,tunnel', 'kubectl port-forward pod-name 8080:80', NULL, NULL, 'kubectl proxy', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (73, 'Network', 'nmap', 'اسکن پورت و شبکه', 'scan,ports,discovery', 'nmap -sV 192.168.1.1', NULL, NULL, 'netstat, ping', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (74, 'Network', 'traceroute', 'مسیر یابی بسته‌ها در شبکه', 'route,path,hop', 'traceroute google.com', NULL, NULL, 'ping, tracepath', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (75, 'Network', 'telnet', 'اتصال به پورت‌های شبکه', 'remote,port,connect', 'telnet example.com 80', NULL, NULL, 'ssh, nc', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (76, 'Network', 'nc (netcat)', 'ابزار شبکه همه منظوره', 'network,port,transfer', 'nc -zv google.com 80', NULL, NULL, 'telnet, ncat', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (77, 'Network', 'ifconfig', 'نمایش تنظیمات شبکه', 'ip,interface,network', 'ifconfig eth0', NULL, NULL, 'ip addr, nmcli', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (78, 'Network', 'ip', 'مدیریت پیشرفته شبکه', 'address,route,link', 'ip addr show', NULL, NULL, 'ifconfig, route', NULL, NULL, NULL, NULL, NULL, '2026-04-30 22:45:04');
INSERT INTO `commands` VALUES (79, 'Ansible', 'ansible --version', 'نمایش نسخه Ansible', 'version,info', 'ansible --version', NULL, NULL, 'ansible-config dump', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:26');
INSERT INTO `commands` VALUES (80, 'Ansible', 'ansible all -m ping', 'پینگ به همه سرورها', 'test,connectivity,ping', 'ansible all -m ping -i inventory.ini', NULL, NULL, 'ansible-playbook', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:26');
INSERT INTO `commands` VALUES (81, 'Ansible', 'ansible-playbook playbook.yml', 'اجرای پلی بوک', 'run,playbook,execute', 'ansible-playbook site.yml --check', NULL, NULL, 'ansible-pull', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:26');
INSERT INTO `commands` VALUES (82, 'Ansible', 'ansible-doc module_name', 'مشاهده مستندات ماژول', 'documentation,help,module', 'ansible-doc copy', NULL, NULL, 'ansible-doc -l', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:26');
INSERT INTO `commands` VALUES (83, 'Ansible', 'ansible-galaxy init role_name', 'ساخت نقش جدید', 'create,scaffold,role', 'ansible-galaxy init myrole', NULL, NULL, 'ansible-galaxy install', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:26');
INSERT INTO `commands` VALUES (84, 'Ansible', 'ansible-vault encrypt secret.yml', 'رمزنگاری فایل', 'encrypt,secure,password', 'ansible-vault encrypt secrets.yml', NULL, NULL, 'ansible-vault decrypt', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:26');
INSERT INTO `commands` VALUES (85, 'Ansible', 'ansible-inventory --list', 'لیست موجودی سرورها', 'inventory,hosts,list', 'ansible-inventory --list -i inventory.ini', NULL, NULL, 'ansible all --list-hosts', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:26');
INSERT INTO `commands` VALUES (86, 'Ansible', 'ansible-config dump', 'نمایش تنظیمات فعلی', 'config,settings,show', 'ansible-config dump --only-changed', NULL, NULL, 'ansible --version', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:26');
INSERT INTO `commands` VALUES (87, 'Ansible', 'ansible webservers -m apt -a \"name=nginx state=present\"', 'نصب پکیج با apt', 'install,package,apt', 'ansible webservers -m apt -a \"name=nginx state=present\"', NULL, NULL, 'ansible webservers -m yum', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:26');
INSERT INTO `commands` VALUES (88, 'Ansible', 'ansible all -m copy -a \"src=/local/file dest=/remote/file\"', 'کپی فایل به سرورها', 'copy,file,transfer', 'ansible all -m copy -a \"src=/etc/hosts dest=/tmp/hosts\"', NULL, NULL, 'ansible all -m fetch', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:26');
INSERT INTO `commands` VALUES (89, 'Ansible', 'ansible all -m shell -a \"uptime\"', 'اجرای دستور shell', 'command,execute,shell', 'ansible all -m shell -a \"df -h\"', NULL, NULL, 'ansible all -m command', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:26');
INSERT INTO `commands` VALUES (90, 'Ansible', 'ansible-playbook playbook.yml --check', 'حالت Dry-run', 'check,test,simulate', 'ansible-playbook playbook.yml --check', NULL, NULL, 'ansible-playbook playbook.yml --diff', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:26');
INSERT INTO `commands` VALUES (91, 'Ansible', 'ansible-playbook playbook.yml --tags \"setup\"', 'اجرای فقط تگ مشخص', 'tags,filter,selective', 'ansible-playbook playbook.yml --tags \"install,config\"', NULL, NULL, 'ansible-playbook playbook.yml --skip-tags', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:26');
INSERT INTO `commands` VALUES (92, 'Ansible', 'ansible-playbook playbook.yml -v', 'حالت verbose برای دیباگ', 'verbose,debug,output', 'ansible-playbook playbook.yml -vvv', NULL, NULL, 'ansible-playbook playbook.yml --step', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:26');
INSERT INTO `commands` VALUES (93, 'Ansible', 'ansible all -m setup | grep ansible_os_family', 'نمایش facts سرورها', 'facts,info,system', 'ansible all -m setup | grep \"os_family\"', NULL, NULL, 'ansible all -m gather_facts', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:26');
INSERT INTO `commands` VALUES (94, 'Kubernetes', 'kubectl get pods --all-namespaces', 'لیست پادها در همه نام‌فضاها', 'pods,all,wide', 'kubectl get pods -A -o wide', NULL, NULL, 'kubectl get pods -n namespace', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:34');
INSERT INTO `commands` VALUES (95, 'Kubernetes', 'kubectl describe pod pod-name', 'جزئیات کامل یک پاد', 'describe,details,info', 'kubectl describe pod nginx-pod', NULL, NULL, 'kubectl get pod -o yaml', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:34');
INSERT INTO `commands` VALUES (96, 'Kubernetes', 'kubectl logs -f pod-name', 'مشاهده لحظه‌ای لاگ پاد', 'logs,stream,follow', 'kubectl logs -f nginx-pod', NULL, NULL, 'kubectl logs pod-name --previous', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:34');
INSERT INTO `commands` VALUES (97, 'Kubernetes', 'kubectl exec -it pod-name -- /bin/bash', 'ورود به شل پاد', 'exec,shell,interactive', 'kubectl exec -it nginx-pod -- /bin/bash', NULL, NULL, 'kubectl attach', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:34');
INSERT INTO `commands` VALUES (98, 'Kubernetes', 'kubectl port-forward pod-name 8080:80', 'فوروارد کردن پورت به پاد', 'port,forward,tunnel', 'kubectl port-forward nginx-pod 8080:80', NULL, NULL, 'kubectl proxy', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:34');
INSERT INTO `commands` VALUES (99, 'Kubernetes', 'kubectl apply -f deployment.yaml', 'اعمال کانفیگ از فایل', 'apply,create,deploy', 'kubectl apply -f nginx-deployment.yaml', NULL, NULL, 'kubectl create -f', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:34');
INSERT INTO `commands` VALUES (100, 'Kubernetes', 'kubectl delete -f deployment.yaml', 'حذف منابع از فایل', 'delete,remove,clean', 'kubectl delete -f nginx-deployment.yaml', NULL, NULL, 'kubectl delete pod pod-name', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:34');
INSERT INTO `commands` VALUES (101, 'Kubernetes', 'kubectl rollout status deployment/nginx', 'وضعیت رول اوت', 'rollout,status,progress', 'kubectl rollout status deployment/nginx', NULL, NULL, 'kubectl rollout history', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:34');
INSERT INTO `commands` VALUES (102, 'Kubernetes', 'kubectl rollout undo deployment/nginx', 'برگرداندن به نسخه قبل', 'rollback,undo,revert', 'kubectl rollout undo deployment/nginx', NULL, NULL, 'kubectl rollout history', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:34');
INSERT INTO `commands` VALUES (103, 'Kubernetes', 'kubectl scale deployment/nginx --replicas=5', 'مقیاس دهی تعداد پادها', 'scale,replicas,horizontal', 'kubectl scale deployment/nginx --replicas=5', NULL, NULL, 'kubectl autoscale', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:34');
INSERT INTO `commands` VALUES (104, 'Kubernetes', 'kubectl get events --sort-by=.metadata.creationTimestamp', 'مشاهده رویدادها', 'events,logs,audit', 'kubectl get events --sort-by=.metadata.creationTimestamp', NULL, NULL, 'kubectl describe node', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:34');
INSERT INTO `commands` VALUES (105, 'Kubernetes', 'kubectl top pods', 'نمایش مصرف منابع پادها', 'metrics,cpu,memory', 'kubectl top pods --all-namespaces', NULL, NULL, 'kubectl top nodes', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:34');
INSERT INTO `commands` VALUES (106, 'Kubernetes', 'kubectl get configmap', 'لیست ConfigMap ها', 'configmap,configuration,env', 'kubectl get configmap', NULL, NULL, 'kubectl describe configmap', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:34');
INSERT INTO `commands` VALUES (107, 'Kubernetes', 'kubectl get secrets', 'لیست Secrets', 'secrets,password,token', 'kubectl get secrets', NULL, NULL, 'kubectl describe secret', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:34');
INSERT INTO `commands` VALUES (108, 'Kubernetes', 'kubectl create secret generic my-secret --from-literal=key=value', 'ساخت Secret جدید', 'create,secret,literal', 'kubectl create secret generic db-pass --from-literal=password=123', NULL, NULL, 'kubectl create secret docker-registry', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:34');
INSERT INTO `commands` VALUES (109, 'Kubernetes', 'kubectl get ingress', 'لیست Ingress ها', 'ingress,routing,dns', 'kubectl get ingress -A', NULL, NULL, 'kubectl describe ingress', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:34');
INSERT INTO `commands` VALUES (110, 'Kubernetes', 'kubectl get persistentvolume', 'لیست حجم‌های پایدار', 'pv,storage,volume', 'kubectl get pv', NULL, NULL, 'kubectl get pvc', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:34');
INSERT INTO `commands` VALUES (111, 'Kubernetes', 'kubectl cordon node-name', 'مسدود کردن زمانبندی روی نود', 'cordon,node,maintenance', 'kubectl cordon worker-node1', NULL, NULL, 'kubectl uncordon', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:34');
INSERT INTO `commands` VALUES (112, 'Kubernetes', 'kubectl drain node-name --ignore-daemonsets', 'تخلیه نود برای نگهداری', 'drain,maintenance,migrate', 'kubectl drain worker-node1 --ignore-daemonsets', NULL, NULL, 'kubectl delete node', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:34');
INSERT INTO `commands` VALUES (113, 'Kubernetes', 'kubectl get crd', 'لیست Custom Resource Definitions', 'crd,custom,resources', 'kubectl get crd', NULL, NULL, 'kubectl explain crd', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:34');
INSERT INTO `commands` VALUES (114, 'MongoDB', 'mongod', 'اجرای سرور MongoDB', 'start,server,database', 'mongod --dbpath /data/db', NULL, NULL, 'mongos, mongo', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:39');
INSERT INTO `commands` VALUES (115, 'MongoDB', 'mongo', 'اتصال به شل MongoDB', 'connect,shell,client', 'mongo mongodb://localhost:27017', NULL, NULL, 'mongosh', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:39');
INSERT INTO `commands` VALUES (116, 'MongoDB', 'show dbs', 'لیست تمام دیتابیس‌ها', 'databases,list,show', 'show dbs', NULL, NULL, 'db.adminCommand', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:39');
INSERT INTO `commands` VALUES (117, 'MongoDB', 'use dbname', 'تغییر یا ساخت دیتابیس', 'switch,create,database', 'use mydb', NULL, NULL, 'show dbs', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:39');
INSERT INTO `commands` VALUES (118, 'MongoDB', 'show collections', 'لیست مجموعه‌ها', 'tables,collections,list', 'show collections', NULL, NULL, 'db.getCollectionNames()', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:39');
INSERT INTO `commands` VALUES (119, 'MongoDB', 'db.collection.find()', 'جستجو در مجموعه', 'query,find,search', 'db.users.find({age: {\\$gt: 18}})', NULL, NULL, 'db.collection.findOne()', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:39');
INSERT INTO `commands` VALUES (120, 'MongoDB', 'db.collection.insertOne()', 'درج یک سند', 'insert,add,create', 'db.users.insertOne({name: \"John\", age: 30})', NULL, NULL, 'db.collection.insertMany()', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:39');
INSERT INTO `commands` VALUES (121, 'MongoDB', 'db.collection.updateOne()', 'به‌روزرسانی یک سند', 'update,modify,edit', 'db.users.updateOne({name: \"John\"}, {\\$set: {age: 31}})', NULL, NULL, 'db.collection.updateMany()', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:39');
INSERT INTO `commands` VALUES (122, 'MongoDB', 'db.collection.deleteOne()', 'حذف یک سند', 'delete,remove,erase', 'db.users.deleteOne({name: \"John\"})', NULL, NULL, 'db.collection.deleteMany()', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:39');
INSERT INTO `commands` VALUES (123, 'MongoDB', 'db.collection.countDocuments()', 'تعداد اسناد', 'count,total,size', 'db.users.countDocuments({age: {\\$gt: 18}})', NULL, NULL, 'db.collection.estimatedDocumentCount()', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:39');
INSERT INTO `commands` VALUES (124, 'MongoDB', 'db.collection.createIndex()', 'ساخت ایندکس', 'index,optimize,performance', 'db.users.createIndex({email: 1})', NULL, NULL, 'db.collection.getIndexes()', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:39');
INSERT INTO `commands` VALUES (125, 'MongoDB', 'db.collection.aggregate()', 'aggregation pipeline', 'aggregate,group,calculate', 'db.orders.aggregate([{\\$group: {_id: \"\\$status\", total: {\\$sum: 1}}}])', NULL, NULL, 'db.collection.mapReduce()', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:39');
INSERT INTO `commands` VALUES (126, 'MongoDB', 'mongodump', 'بکاپ گرفتن از دیتابیس', 'backup,export,dump', 'mongodump --db mydb --out /backup', NULL, NULL, 'mongorestore', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:39');
INSERT INTO `commands` VALUES (127, 'MongoDB', 'mongorestore', 'بازگردانی بکاپ', 'restore,import,recover', 'mongorestore --db mydb /backup/mydb', NULL, NULL, 'mongodump', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:39');
INSERT INTO `commands` VALUES (128, 'MongoDB', 'db.stats()', 'آمار دیتابیس', 'statistics,size,info', 'db.stats()', NULL, NULL, 'db.collection.stats()', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:39');
INSERT INTO `commands` VALUES (129, 'Nginx', 'nginx -t', 'تست صحت تنظیمات Nginx', 'test,config,validate', 'nginx -t', NULL, NULL, 'nginx -T', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:45');
INSERT INTO `commands` VALUES (130, 'Nginx', 'nginx -s reload', 'بارگذاری مجدد تنظیمات بدون قطعی', 'reload,restart,config', 'nginx -s reload', NULL, NULL, 'systemctl reload nginx', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:45');
INSERT INTO `commands` VALUES (131, 'Nginx', 'nginx -s stop', 'توقف سریع Nginx', 'stop,kill,emergency', 'nginx -s stop', NULL, NULL, 'nginx -s quit', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:45');
INSERT INTO `commands` VALUES (132, 'Nginx', 'nginx -s quit', 'توقف ملایم Nginx', 'stop,graceful,shutdown', 'nginx -s quit', NULL, NULL, 'kill -QUIT', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:45');
INSERT INTO `commands` VALUES (133, 'Nginx', 'systemctl start nginx', 'شروع سرویس Nginx', 'start,service,launch', 'systemctl start nginx', NULL, NULL, 'service nginx start', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:45');
INSERT INTO `commands` VALUES (134, 'Nginx', 'systemctl status nginx', 'وضعیت سرویس Nginx', 'status,check,running', 'systemctl status nginx', NULL, NULL, 'ps aux | grep nginx', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:45');
INSERT INTO `commands` VALUES (135, 'Nginx', 'journalctl -u nginx -f', 'مشاهده لاگ‌های Nginx', 'logs,debug,error', 'journalctl -u nginx -f', NULL, NULL, 'tail -f /var/log/nginx/error.log', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:45');
INSERT INTO `commands` VALUES (136, 'Nginx', 'vim /etc/nginx/nginx.conf', 'ویرایش فایل اصلی کانفیگ', 'edit,config,main', 'vim /etc/nginx/nginx.conf', NULL, NULL, 'nano /etc/nginx/nginx.conf', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:45');
INSERT INTO `commands` VALUES (137, 'Nginx', 'ls /etc/nginx/sites-available/', 'لیست سایت‌های فعال', 'sites,config,available', 'ls /etc/nginx/sites-available/', NULL, NULL, 'ls /etc/nginx/sites-enabled/', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:45');
INSERT INTO `commands` VALUES (138, 'Nginx', 'ln -s /etc/nginx/sites-available/mysite /etc/nginx/sites-enabled/', 'فعال کردن سایت جدید', 'enable,site,symlink', 'ln -s /etc/nginx/sites-available/mysite /etc/nginx/sites-enabled/', NULL, NULL, 'unlink /etc/nginx/sites-enabled/mysite', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:45');
INSERT INTO `commands` VALUES (139, 'PostgreSQL', 'psql -U username -d dbname', 'اتصال به دیتابیس PostgreSQL', 'connect,login,access', 'psql -U postgres -d mydb', NULL, NULL, 'pgcli, pgadmin', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:51');
INSERT INTO `commands` VALUES (140, 'PostgreSQL', '\\l', 'لیست تمام دیتابیس‌ها', 'list,databases,show', '\\l', NULL, NULL, 'SELECT datname FROM pg_database;', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:51');
INSERT INTO `commands` VALUES (141, 'PostgreSQL', '\\c dbname', 'اتصال به دیتابیس مشخص', 'connect,switch,use', '\\c mydb', NULL, NULL, 'CONNECT TO', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:51');
INSERT INTO `commands` VALUES (142, 'PostgreSQL', '\\dt', 'لیست جدول‌های دیتابیس جاری', 'tables,list,schema', '\\dt', NULL, NULL, '\\d, \\d+', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:51');
INSERT INTO `commands` VALUES (143, 'PostgreSQL', '\\d tablename', 'نمایش ساختار جدول', 'describe,schema,columns', '\\d users', NULL, NULL, '\\d+, \\dt', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:51');
INSERT INTO `commands` VALUES (144, 'PostgreSQL', '\\du', 'لیست کاربران دیتابیس', 'users,roles,list', '\\du', NULL, NULL, 'SELECT * FROM pg_user;', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:51');
INSERT INTO `commands` VALUES (145, 'PostgreSQL', 'CREATE DATABASE dbname;', 'ساخت دیتابیس جدید', 'create,database,new', 'CREATE DATABASE myapp;', NULL, NULL, 'createdb, DROP DATABASE', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:51');
INSERT INTO `commands` VALUES (146, 'PostgreSQL', 'DROP DATABASE dbname;', 'حذف دیتابیس', 'delete,remove,database', 'DROP DATABASE IF EXISTS myapp;', NULL, NULL, 'CREATE DATABASE', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:51');
INSERT INTO `commands` VALUES (147, 'PostgreSQL', 'CREATE USER username WITH PASSWORD \'pass\';', 'ساخت کاربر جدید', 'user,create,role', 'CREATE USER john WITH PASSWORD \'123456\';', NULL, NULL, 'ALTER USER, DROP USER', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:51');
INSERT INTO `commands` VALUES (148, 'PostgreSQL', 'GRANT ALL PRIVILEGES ON DATABASE dbname TO username;', 'دادن دسترسی به کاربر', 'permissions,access,grant', 'GRANT ALL PRIVILEGES ON DATABASE mydb TO john;', NULL, NULL, 'REVOKE', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:51');
INSERT INTO `commands` VALUES (149, 'PostgreSQL', '\\q', 'خروج از محیط psql', 'exit,quit,logout', '\\q', NULL, NULL, 'exit, Ctrl+D', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:51');
INSERT INTO `commands` VALUES (150, 'PostgreSQL', 'pg_dump dbname > backup.sql', 'بکاپ گرفتن از دیتابیس', 'backup,export,dump', 'pg_dump mydb > backup.sql', NULL, NULL, 'pg_dumpall, pg_restore', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:51');
INSERT INTO `commands` VALUES (151, 'PostgreSQL', 'pg_dumpall > all_backup.sql', 'بکاپ کامل از همه دیتابیس‌ها', 'backup,full,all', 'pg_dumpall > all_backup.sql', NULL, NULL, 'pg_dump', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:51');
INSERT INTO `commands` VALUES (152, 'PostgreSQL', 'pg_restore -d dbname backup.sql', 'بازگردانی بکاپ', 'restore,import,recover', 'pg_restore -d mydb backup.sql', NULL, NULL, 'psql < backup.sql', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:51');
INSERT INTO `commands` VALUES (153, 'PostgreSQL', 'psql -d dbname -f script.sql', 'اجرای فایل SQL', 'execute,run,script', 'psql -d mydb -f init.sql', NULL, NULL, '\\i script.sql', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:51');
INSERT INTO `commands` VALUES (154, 'PostgreSQL', 'SELECT version();', 'نمایش نسخه PostgreSQL', 'version,info,status', 'SELECT version();', NULL, NULL, 'SHOW server_version;', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:51');
INSERT INTO `commands` VALUES (155, 'PostgreSQL', '\\timing', 'فعال کردن زمان اجرای کوئری‌ها', 'timer,performance,query', '\\timing', NULL, NULL, 'EXPLAIN ANALYZE', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:51');
INSERT INTO `commands` VALUES (156, 'PostgreSQL', 'EXPLAIN ANALYZE SELECT * FROM table;', 'تحلیل و بهینه‌سازی کوئری', 'performance,analyze,optimize', 'EXPLAIN ANALYZE SELECT * FROM users WHERE age > 18;', NULL, NULL, 'EXPLAIN, \\timing', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:51');
INSERT INTO `commands` VALUES (157, 'PostgreSQL', 'VACUUM;', 'پاکسازی و بهینه‌سازی دیتابیس', 'clean,optimize,maintenance', 'VACUUM ANALYZE;', NULL, NULL, 'VACUUM FULL, ANALYZE', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:51');
INSERT INTO `commands` VALUES (158, 'PostgreSQL', 'pg_stat_activity', 'مشاهده کوئری‌های در حال اجرا', 'running queries,monitor', 'SELECT * FROM pg_stat_activity;', NULL, NULL, 'pg_locks', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:51');
INSERT INTO `commands` VALUES (159, 'Redis', 'redis-server', 'اجرای سرور Redis', 'start,server,database', 'redis-server --port 6379', NULL, NULL, 'redis-server /etc/redis.conf', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:55');
INSERT INTO `commands` VALUES (160, 'Redis', 'redis-cli', 'اتصال به Redis CLI', 'connect,client,shell', 'redis-cli -h localhost -p 6379', NULL, NULL, 'redis-cli -a password', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:55');
INSERT INTO `commands` VALUES (161, 'Redis', 'SET key value', 'ذخیره مقدار در کلید', 'store,save,insert', 'SET username \"John\"', NULL, NULL, 'SETEX, SETNX', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:55');
INSERT INTO `commands` VALUES (162, 'Redis', 'GET key', 'دریافت مقدار کلید', 'retrieve,value,read', 'GET username', NULL, NULL, 'MGET, GETSET', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:55');
INSERT INTO `commands` VALUES (163, 'Redis', 'DEL key', 'حذف کلید', 'delete,remove,erase', 'DEL username', NULL, NULL, 'UNLINK, FLUSHDB', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:55');
INSERT INTO `commands` VALUES (164, 'Redis', 'EXISTS key', 'بررسی وجود کلید', 'check,exists,has', 'EXISTS username', NULL, NULL, 'TYPE, TTL', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:55');
INSERT INTO `commands` VALUES (165, 'Redis', 'EXPIRE key seconds', 'تنظیم زمان انقضا', 'ttl,timeout,expiry', 'EXPIRE session 3600', NULL, NULL, 'PEXPIRE, EXPIREAT', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:55');
INSERT INTO `commands` VALUES (166, 'Redis', 'KEYS pattern', 'جستجوی کلیدها', 'search,find,pattern', 'KEYS user:*', NULL, NULL, 'SCAN', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:55');
INSERT INTO `commands` VALUES (167, 'Redis', 'HSET key field value', 'ذخیره در هش', 'hash,object,store', 'HSET user:100 name \"John\" age 30', NULL, NULL, 'HGET, HMSET', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:55');
INSERT INTO `commands` VALUES (168, 'Redis', 'HGET key field', 'دریافت از هش', 'hash,retrieve,field', 'HGET user:100 name', NULL, NULL, 'HGETALL, HMGET', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:55');
INSERT INTO `commands` VALUES (169, 'Redis', 'LPUSH key value', 'افزودن به لیست (سمت چپ)', 'list,push,queue', 'LPUSH tasks \"task1\"', NULL, NULL, 'RPUSH, LPOP', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:55');
INSERT INTO `commands` VALUES (170, 'Redis', 'SAVE', 'ذخیره لحظه‌ای دیتا در دیسک', 'backup,persist,snapshot', 'SAVE', NULL, NULL, 'BGSAVE, LASTSAVE', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:55');
INSERT INTO `commands` VALUES (171, 'Terraform', 'terraform init', 'آماده‌سازی دایرکتوری و دانلود پلاگین‌ها', 'initialize,download,plugins', 'terraform init -upgrade', NULL, NULL, 'terraform get', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:59');
INSERT INTO `commands` VALUES (172, 'Terraform', 'terraform plan', 'نمایش تغییرات پیش از اجرا', 'plan,preview,dry-run', 'terraform plan -out=tfplan', NULL, NULL, 'terraform show', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:59');
INSERT INTO `commands` VALUES (173, 'Terraform', 'terraform apply', 'اعمال تغییرات روی زیرساخت', 'apply,deploy,create', 'terraform apply -auto-approve', NULL, NULL, 'terraform apply tfplan', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:59');
INSERT INTO `commands` VALUES (174, 'Terraform', 'terraform destroy', 'حذف کامل زیرساخت', 'destroy,delete,cleanup', 'terraform destroy -auto-approve', NULL, NULL, 'terraform apply -destroy', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:59');
INSERT INTO `commands` VALUES (175, 'Terraform', 'terraform validate', 'اعتبارسنجی فایل‌های کانفیگ', 'validate,check,syntax', 'terraform validate', NULL, NULL, 'terraform fmt -check', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:59');
INSERT INTO `commands` VALUES (176, 'Terraform', 'terraform fmt', 'فرمت کردن فایل‌ها استاندارد', 'format,style,beautify', 'terraform fmt -recursive', NULL, NULL, 'terraform fmt -diff', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:59');
INSERT INTO `commands` VALUES (177, 'Terraform', 'terraform show', 'نمایش وضعیت فعلی', 'state,show,output', 'terraform show -json', NULL, NULL, 'terraform state list', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:59');
INSERT INTO `commands` VALUES (178, 'Terraform', 'terraform state list', 'لیست منابع در state', 'resources,state,list', 'terraform state list', NULL, NULL, 'terraform state show', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:59');
INSERT INTO `commands` VALUES (179, 'Terraform', 'terraform output', 'نمایش outputها', 'output,values,variables', 'terraform output instance_ip', NULL, NULL, 'terraform output -json', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:59');
INSERT INTO `commands` VALUES (180, 'Terraform', 'terraform workspace new dev', 'ساخت workspace جدید', 'workspace,environment,isolate', 'terraform workspace new production', NULL, NULL, 'terraform workspace select', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:59');
INSERT INTO `commands` VALUES (181, 'Terraform', 'terraform plan -destroy', 'برنامه ریزی برای حذف', 'destroy,delete,plan', 'terraform plan -destroy -out=destroy.tfplan', NULL, NULL, 'terraform destroy', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:59');
INSERT INTO `commands` VALUES (182, 'Terraform', 'terraform refresh', 'به‌روزرسانی state با منابع واقعی', 'refresh,sync,update', 'terraform refresh', NULL, NULL, 'terraform apply -refresh-only', NULL, NULL, NULL, NULL, NULL, '2026-04-30 23:00:59');
INSERT INTO `commands` VALUES (185, 'Linux', '\"ls -la\"', '\"لیست فایل‌ها با جزئیات\"', '', '', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, '2026-05-01 00:38:33');
INSERT INTO `commands` VALUES (186, 'Docker', '\"docker ps\"', '\"لیست کانتینرهای در حال اجرا\"', '', '', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, '2026-05-01 00:38:33');
INSERT INTO `commands` VALUES (187, 'Git', '\"git status\"', '\"وضعیت مخزن گیت\"', '', '', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, '2026-05-01 00:38:33');
INSERT INTO `commands` VALUES (188, 'Linux', '\"grep -r \'text\' .\"', '\"جستجوی بازگشتی متن در تمام فایل‌های دایرکتوری جاری\"', '\"search, recursive, text, find\"', '\"grep -r \'error\' /var/log/\"', NULL, NULL, '\"find, ack, rg\"', NULL, NULL, NULL, NULL, NULL, '2026-05-01 00:39:22');
INSERT INTO `commands` VALUES (189, 'Linux', '\"chmod 755 script.sh\"', '\"تنظیم دسترسی‌های فایل: مالک میتواند بخواند، بنویسد و اجرا کند، گروه و دیگران فقط خواندن و اجرا\"', '\"permissions, executable, access\"', '\"chmod 755 deploy.sh\"', NULL, NULL, '\"chown, chgrp, umask\"', NULL, NULL, NULL, NULL, NULL, '2026-05-01 00:39:22');
INSERT INTO `commands` VALUES (190, 'Docker', '\"docker ps -a\"', '\"نمایش همه کانتینرها (در حال اجرا و متوقف شده) به همراه اطلاعات کامل\"', '\"containers, list, all, running\"', '\"docker ps -a --format \'table {{.Names}}\\t{{.Status}}\'\"', NULL, NULL, '\"docker stats, docker top\"', NULL, NULL, NULL, NULL, NULL, '2026-05-01 00:39:22');
INSERT INTO `commands` VALUES (191, 'Docker', '\"docker images\"', '\"نمایش لیست ایمیج‌های دانلود شده روی سیستم\"', '\"images, list, local, repository\"', '\"docker images --filter \'dangling=false\'\"', NULL, NULL, '\"docker rmi, docker pull\"', NULL, NULL, NULL, NULL, NULL, '2026-05-01 00:39:22');
INSERT INTO `commands` VALUES (192, 'Docker', '\"docker-compose up -d\"', '\"اجرای سرویس‌های تعریف شده در فایل docker-compose.yml در حالت دیمن (background)\"', '\"compose, orchestrate, multi-container\"', '\"docker-compose -f docker-compose.prod.yml up -d\"', NULL, NULL, '\"docker-compose down, docker-compose logs\"', NULL, NULL, NULL, NULL, NULL, '2026-05-01 00:39:22');
INSERT INTO `commands` VALUES (193, 'Git', '\"git log --oneline --graph\"', '\"نمایش تاریخچه کامیت‌ها به صورت گرافیکی و فشرده\"', '\"history, commits, graph, tree\"', '\"git log --oneline --graph --all\"', NULL, NULL, '\"git show, git diff\"', NULL, NULL, NULL, NULL, NULL, '2026-05-01 00:39:22');
INSERT INTO `commands` VALUES (194, 'Network', '\"netstat -tuln\"', '\"نمایش پورت‌های باز و سرویس‌های در حال گوش دادن\"', '\"ports, listening, network, connections\"', '\"netstat -tulnp | grep LISTEN\"', NULL, NULL, '\"ss, lsof, nmap\"', NULL, NULL, NULL, NULL, NULL, '2026-05-01 00:39:22');
INSERT INTO `commands` VALUES (195, 'Network', '\"curl -I https://example.com\"', '\"دریافت هدرهای HTTP یک سایت برای بررسی وضعیت\"', '\"http, headers, status, request\"', '\"curl -I https://google.com\"', NULL, NULL, '\"wget, httpie\"', NULL, NULL, NULL, NULL, NULL, '2026-05-01 00:39:22');
INSERT INTO `commands` VALUES (196, 'Database', '\"mysql -u root -p -e \'SHOW DATABASES;\'\"', '\"اتصال به MySQL و نمایش لیست دیتابیس‌ها\"', '\"mysql, database, list, show\"', '\"mysql -u admin -p -e \'SELECT VERSION();\'\"', NULL, NULL, '\"psql, mongosh\"', NULL, NULL, NULL, NULL, NULL, '2026-05-01 00:39:22');
INSERT INTO `commands` VALUES (197, 'Database', '\"pg_dump mydb > backup.sql\"', '\"بکاپ گرفتن از دیتابیس PostgreSQL\"', '\"postgresql, backup, export, dump\"', '\"pg_dump -U postgres mydb > backup_$(date +%Y%m%d).sql\"', NULL, NULL, '\"pg_restore, psql\"', NULL, NULL, NULL, NULL, NULL, '2026-05-01 00:39:22');
INSERT INTO `commands` VALUES (198, 'Docker', 'yam-add-file-command', 'تست فایل برای اضافه کردن دستورات به دیتابیس', 'yaml', '# نمونه دستورات لینوکس\r\n- command: \"ls -la\"\r\n  category: \"Linux\"\r\n  description: \"نمایش لیست کامل فایل‌ها و پوشه‌ها با جزئیات کامل شامل مجوزها، مالک، اندازه و تاریخ\"\r\n  keywords: \"list, files, detailed, permissions\"\r\n  example: \"ls -la /home/user/Documents\"\r\n  similar: \"ll, dir, ls -l\"', NULL, NULL, 'docker compose', NULL, NULL, NULL, NULL, NULL, '2026-05-01 00:41:20');
INSERT INTO `commands` VALUES (199, 'PostgreSQL', 'psql -U admin -d devops_db', 'ورود به PostgreSQL با کاربر admin', 'postgres,login,connect,database', 'docker exec -it postgres psql -U admin -d devops_db', NULL, NULL, 'psql -U postgres, pg_isready', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (200, 'PostgreSQL', 'CREATE DATABASE', 'ساخت دیتابیس جدید', 'create,database,new,db', 'CREATE DATABASE jiradb OWNER jiradbuser;', NULL, NULL, 'createdb, CREATE SCHEMA', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (201, 'PostgreSQL', 'CREATE USER', 'ساخت کاربر جدید در PostgreSQL', 'user,create,role,account', 'CREATE USER mahpooya WITH PASSWORD \'m123456\';', NULL, NULL, 'CREATE ROLE, ALTER USER', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (202, 'PostgreSQL', 'GRANT ALL PRIVILEGES', 'اعطای همه دسترسی‌ها به کاربر روی دیتابیس', 'grant,privileges,access,permission', 'GRANT ALL PRIVILEGES ON DATABASE jiradb TO jiradbuser;', NULL, NULL, 'GRANT SELECT, GRANT INSERT', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (203, 'PostgreSQL', 'pg_dump', 'بکاپ‌گیری از دیتابیس', 'backup,dump,export,sql', 'docker exec postgres pg_dump -U admin -d devops_db > backup.sql', NULL, NULL, 'pg_restore, COPY', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (204, 'GitLab', 'gitlab-rails console', 'ورود به کنسول Rails گیت‌لب', 'console,rails,admin,manage', 'docker exec -it gitlab gitlab-rails console', NULL, NULL, 'gitlab-rails runner', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (205, 'GitLab', 'gitlab-ctl reconfigure', 'بازپیکربندی گیت‌لب بعد از تغییر تنظیمات', 'reconfigure,apply,restart,config', 'docker exec -it gitlab gitlab-ctl reconfigure', NULL, NULL, 'gitlab-ctl restart', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (206, 'GitLab', 'gitlab-ctl status', 'وضعیت سرویس‌های داخلی گیت‌لب', 'status,health,services,check', 'docker exec -it gitlab gitlab-ctl status', NULL, NULL, 'gitlab-ctl tail', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (207, 'GitLab', 'initial_root_password', 'دریافت رمز عبور root اولیه', 'password,root,initial,reset', 'docker exec gitlab cat /etc/gitlab/initial_root_password', NULL, NULL, 'gitlab-rails runner \"User.find(1).password\"', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (208, 'GitLab', 'docker exec -it gitlab gitlab-rails runner \'u=User.first; u.password=\"Admin@123!\"; u.save!\'', 'تغییر رمز کاربر root گیت‌لب', 'password,change,reset,root', 'docker exec -it gitlab gitlab-rails runner \'u=User.first; u.password=\"Admin@123!\"; u.save!\'', NULL, NULL, 'gitlab-rails console', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (209, 'GitLab', 'git clone SSH', 'کلون مخزن با SSH (پورت 2222)', 'clone,ssh,repository,download', 'git clone ssh://git@192.168.137.50:2222/root/project.git', NULL, NULL, 'git clone --mirror', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (210, 'GitLab', 'git push SSH', 'آپلود کد به مخزن با SSH', 'push,upload,ssh,commit', 'git push -u origin main', NULL, NULL, 'git push --force', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (211, 'Jira', 'JIRA_HOME', 'تنظیم دایرکتوری خانه جیرا', 'home,config,setup,path', '-e JIRA_HOME=/var/jira-home (در docker run)', NULL, NULL, 'ATL_JIRA_HOME', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (212, 'Jira', 'Setup دیتابیس جیرا', 'اتصال جیرا به PostgreSQL', 'database,setup,postgres,config', 'Host: postgres, Port: 5432, DB: jiradb, User: jiradbuser, Pass: Admin@123!', NULL, NULL, 'ATL_JDBC_URL', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (213, 'Jira', 'لاگ جیرا', 'مشاهده لاگ خطاهای جیرا', 'logs,error,debug,troubleshoot', 'docker logs jira --tail 50', NULL, NULL, 'docker logs -f jira', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (214, 'Jira', 'ریستارت جیرا', 'راه‌اندازی مجدد کانتینر جیرا', 'restart,reboot,reload', 'docker restart jira', NULL, NULL, 'docker-compose restart jira', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (215, 'Nexus', 'admin.password', 'دریافت رمز عبور اولیه Nexus', 'password,admin,initial,reset', 'docker exec nexus cat /nexus-data/admin.password', NULL, NULL, 'cat /opt/nexus-data/admin.password', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (216, 'Nexus', 'تغییر رمز Nexus با API', 'تغییر رمز کاربر admin از طریق API', 'api,password,change,reset', 'curl -X PUT \'http://localhost:8085/service/rest/v1/security/users/admin/change-password\' -u \'admin:oldpass\' -H \'Content-Type: text/plain\' -d \'Admin@123!\'', NULL, NULL, 'UI change password', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (217, 'Nexus', 'Nexus API وضعیت', 'بررسی وضعیت Nexus از طریق API', 'status,health,api,check', 'curl -u \"admin:Admin@123!\" \"http://192.168.137.50:8085/service/rest/v1/status\"', NULL, NULL, 'curl -I http://localhost:8085', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (218, 'Nexus', 'nexus-data volume', 'مدیریت دیتای Nexus', 'volume,data,storage,backup', 'docker volume rm ops_nexus-data (⚠️ حذف همه دیتا)', NULL, NULL, 'docker volume prune', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (219, 'Network', 'ip route show', 'نمایش جدول مسیریابی', 'route,routing,gateway', 'ip route show default (نمایش گیت‌وی)', NULL, NULL, 'route -n, netstat -rn', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (220, 'Network', 'افزودن گیت‌وی پیش‌فرض', 'تنظیم دستی گیت‌وی اینترنت', 'gateway,add,route,default', 'sudo ip route add default via 192.168.137.1 dev ens224', NULL, NULL, 'nmcli con mod', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (221, 'Network', 'nmcli connection modify', 'تنظیم دائمی گیت‌وی با NetworkManager', 'gateway,static,persistent,nmcli', 'sudo nmcli connection modify \"Profile 1\" ipv4.gateway 192.168.137.1', NULL, NULL, 'nmtui', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (222, 'Network', 'ping 8.8.8.8', 'تست اتصال اینترنت', 'internet,test,connectivity,ping', 'ping -c 4 8.8.8.8', NULL, NULL, 'traceroute, curl', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (223, 'SSL', 'openssl req', 'ساخت گواهی Self-Signed SSL', 'ssl,certificate,https,self-signed', 'openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout server.key -out server.crt', NULL, NULL, 'openssl genrsa', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (224, 'SSL', 'nginx.conf location /jira/', 'تنظیمات پروکسی معکوس جیرا در Nginx', 'proxy,reverse,jira,nginx', 'rewrite ^/jira(/.*)$ $1 break; proxy_pass http://jira:8080;', NULL, NULL, 'proxy_set_header', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (225, 'Nginx', 'nginx.conf upstream', 'تعریف سرویس‌های پشتیبان در Nginx', 'upstream,backend,proxy', 'upstream gitlab { server gitlab:80; }', NULL, NULL, 'proxy_pass', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (226, 'Nginx', 'nginx-proxy ریستارت', 'ریستارت کانتینر reverse proxy', 'restart,reload,nginx', 'docker restart nginx-proxy', NULL, NULL, 'docker exec nginx-proxy nginx -s reload', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (227, 'Nginx', 'بررسی لاگ Nginx', 'مشاهده خطاهای Nginx', 'logs,error,debug,nginx', 'docker logs nginx-proxy --tail 30', NULL, NULL, 'docker logs -f nginx-proxy', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (228, 'Docker', 'docker volume create', 'ساخت volume جدید برای داده‌های ماندگار', 'volume,storage,data,persist', 'docker volume create nexus-data', NULL, NULL, 'docker volume ls', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');
INSERT INTO `commands` VALUES (229, 'Docker', 'docker network connect', 'اتصال کانتینر به شبکه خاص', 'network,connect,isolated', 'docker network connect ops_isolated-net nginx-proxy', NULL, NULL, 'docker network disconnect', NULL, NULL, NULL, NULL, NULL, '2026-05-05 00:10:33');

-- ----------------------------
-- Table structure for user_questions
-- ----------------------------
DROP TABLE IF EXISTS `user_questions`;
CREATE TABLE `user_questions`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `question` text CHARACTER SET utf8 COLLATE utf8_persian_ci NULL,
  `best_answer` text CHARACTER SET utf8 COLLATE utf8_persian_ci NULL,
  `command_id` int NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_persian_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_questions
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
