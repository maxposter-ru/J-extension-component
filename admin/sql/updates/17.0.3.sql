DROP TABLE IF EXISTS `#__maxposter`;

CREATE TABLE `#__maxposter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `value` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__maxposter` VALUES (1,'photo_size','640x480');
INSERT INTO `#__maxposter` VALUES (2,'photo_size','120x90');
INSERT INTO `#__maxposter` VALUES (3,'photo_size','240x180');
