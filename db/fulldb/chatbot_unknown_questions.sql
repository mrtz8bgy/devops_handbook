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

 Date: 05/05/2026 02:47:14
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

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

SET FOREIGN_KEY_CHECKS = 1;
