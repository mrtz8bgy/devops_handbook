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

 Date: 05/05/2026 02:46:59
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

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

SET FOREIGN_KEY_CHECKS = 1;
