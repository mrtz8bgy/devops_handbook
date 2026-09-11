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

 Date: 05/05/2026 02:46:46
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

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

SET FOREIGN_KEY_CHECKS = 1;
