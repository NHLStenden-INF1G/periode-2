/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : Spotlight

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2021-01-19 12:43:16
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for beoordeling
-- ----------------------------
DROP TABLE IF EXISTS `beoordeling`;
CREATE TABLE `beoordeling` (
  `beoordeling_id` int(11) NOT NULL AUTO_INCREMENT,
  `video_id` int(11) NOT NULL,
  `gebruiker_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  PRIMARY KEY (`beoordeling_id`,`gebruiker_id`,`video_id`),
  KEY `fk_beoordeling_video1_idx` (`video_id`),
  KEY `fk_beoordeling_gebruiker1_idx` (`gebruiker_id`),
  CONSTRAINT `fk_beoordeling_gebruikerid` FOREIGN KEY (`gebruiker_id`) REFERENCES `gebruiker` (`gebruiker_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_beoordeling_video1` FOREIGN KEY (`video_id`) REFERENCES `video` (`video_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of beoordeling
-- ----------------------------

-- ----------------------------
-- Table structure for gebruiker
-- ----------------------------
DROP TABLE IF EXISTS `gebruiker`;
CREATE TABLE `gebruiker` (
  `gebruiker_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `voornaam` varchar(100) NOT NULL,
  `achternaam` varchar(100) NOT NULL,
  `wachtwoord` varchar(255) NOT NULL,
  `level` int(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`gebruiker_id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of gebruiker
-- ----------------------------
INSERT INTO `gebruiker` VALUES ('1', 'admin@nhlstenden.com', 'Administrator', ' ', '$2y$10$ljB1iZorlylHJ8HJ5Ibas.A9rr10cOUGGXTc10Cj9Nxe7g1.wcSBq', '3');

-- ----------------------------
-- Table structure for opleiding
-- ----------------------------
DROP TABLE IF EXISTS `opleiding`;
CREATE TABLE `opleiding` (
  `opleiding_id` int(11) NOT NULL AUTO_INCREMENT,
  `jaar` int(11) NOT NULL,
  `periode` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  PRIMARY KEY (`opleiding_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of opleiding
-- ----------------------------

-- ----------------------------
-- Table structure for opmerking
-- ----------------------------
DROP TABLE IF EXISTS `opmerking`;
CREATE TABLE `opmerking` (
  `opmerking_id` int(11) NOT NULL AUTO_INCREMENT,
  `video_id` int(11) NOT NULL,
  `gebruiker_id` int(11) NOT NULL,
  `tekst` text NOT NULL,
  `datum` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`opmerking_id`,`video_id`,`gebruiker_id`),
  KEY `fk_opmerkingen_video1_idx` (`video_id`),
  KEY `fk_opmerkingen_gebruikers1_idx` (`gebruiker_id`),
  CONSTRAINT `fk_opmerkingen_gebruikers1` FOREIGN KEY (`gebruiker_id`) REFERENCES `gebruiker` (`gebruiker_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_opmerkingen_video1` FOREIGN KEY (`video_id`) REFERENCES `video` (`video_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of opmerking
-- ----------------------------

-- ----------------------------
-- Table structure for suggestie
-- ----------------------------
DROP TABLE IF EXISTS `suggestie`;
CREATE TABLE `suggestie` (
  `suggestie_id` int(11) NOT NULL AUTO_INCREMENT,
  `gebruiker_id` int(11) NOT NULL,
  `datum` datetime NOT NULL DEFAULT current_timestamp(),
  `link` text NOT NULL,
  `tekst` text NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`suggestie_id`,`gebruiker_id`),
  KEY `fk_suggestie_gebruiker1` (`gebruiker_id`),
  CONSTRAINT `fk_suggestie_gebruiker1` FOREIGN KEY (`gebruiker_id`) REFERENCES `gebruiker` (`gebruiker_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of suggestie
-- ----------------------------

-- ----------------------------
-- Table structure for tag
-- ----------------------------
DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `naam` varchar(255) NOT NULL,
  PRIMARY KEY (`tag_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of tag
-- ----------------------------

-- ----------------------------
-- Table structure for tag_video
-- ----------------------------
DROP TABLE IF EXISTS `tag_video`;
CREATE TABLE `tag_video` (
  `tag_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  PRIMARY KEY (`tag_id`,`video_id`),
  KEY `fk_video_id` (`video_id`),
  CONSTRAINT `fk_tag_id` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`tag_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_video_id` FOREIGN KEY (`video_id`) REFERENCES `video` (`video_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of tag_video
-- ----------------------------

-- ----------------------------
-- Table structure for vak
-- ----------------------------
DROP TABLE IF EXISTS `vak`;
CREATE TABLE `vak` (
  `vak_id` int(11) NOT NULL AUTO_INCREMENT,
  `opleiding_id` int(11) NOT NULL,
  `vak_naam` varchar(255) NOT NULL,
  PRIMARY KEY (`vak_id`,`opleiding_id`),
  KEY `fk_vakken_opleiding1_idx` (`opleiding_id`),
  CONSTRAINT `fk_vakken_opleiding1` FOREIGN KEY (`opleiding_id`) REFERENCES `opleiding` (`opleiding_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of vak
-- ----------------------------

-- ----------------------------
-- Table structure for video
-- ----------------------------
DROP TABLE IF EXISTS `video`;
CREATE TABLE `video` (
  `video_id` int(11) NOT NULL AUTO_INCREMENT,
  `gebruiker_id` int(11) NOT NULL,
  `titel` varchar(255) NOT NULL,
  `playback_id` varchar(255) DEFAULT NULL,
  `videoPath` varchar(255) DEFAULT NULL,
  `videoLengte` int(11) DEFAULT NULL,
  `uploadDatum` datetime NOT NULL DEFAULT current_timestamp(),
  `views` int(11) NOT NULL,
  PRIMARY KEY (`video_id`,`gebruiker_id`),
  UNIQUE KEY `playback_id_UNIQUE` (`playback_id`),
  KEY `fk_video_gebruikers1_idx` (`gebruiker_id`),
  CONSTRAINT `fk_video_gebruikers1` FOREIGN KEY (`gebruiker_id`) REFERENCES `gebruiker` (`gebruiker_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of video
-- ----------------------------

-- ----------------------------
-- Table structure for video_vak
-- ----------------------------
DROP TABLE IF EXISTS `video_vak`;
CREATE TABLE `video_vak` (
  `video_id` int(11) NOT NULL,
  `vak_id` int(11) NOT NULL,
  PRIMARY KEY (`video_id`,`vak_id`),
  KEY `fk_vakid2_idx` (`vak_id`),
  CONSTRAINT `fk_vakid2` FOREIGN KEY (`vak_id`) REFERENCES `vak` (`vak_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_video_id2` FOREIGN KEY (`video_id`) REFERENCES `video` (`video_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of video_vak
-- ----------------------------

-- ----------------------------
-- Table structure for voortgang
-- ----------------------------
DROP TABLE IF EXISTS `voortgang`;
CREATE TABLE `voortgang` (
  `gebruiker_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `datum` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`gebruiker_id`,`video_id`) USING BTREE,
  KEY `fk_voortgang_video1_idx` (`video_id`),
  CONSTRAINT `fk_video_id3` FOREIGN KEY (`video_id`) REFERENCES `video` (`video_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_voortgang_gebruiker1` FOREIGN KEY (`gebruiker_id`) REFERENCES `gebruiker` (`gebruiker_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of voortgang
-- ----------------------------
