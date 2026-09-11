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

 Date: 05/05/2026 02:46:29
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

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

SET FOREIGN_KEY_CHECKS = 1;
