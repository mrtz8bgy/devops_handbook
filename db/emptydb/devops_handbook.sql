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

 Date: 05/05/2026 02:45:06
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

SET FOREIGN_KEY_CHECKS = 1;
