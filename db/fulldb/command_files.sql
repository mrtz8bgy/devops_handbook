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

 Date: 05/05/2026 02:47:34
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

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

SET FOREIGN_KEY_CHECKS = 1;
