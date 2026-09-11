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

 Date: 05/05/2026 02:45:58
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

SET FOREIGN_KEY_CHECKS = 1;
