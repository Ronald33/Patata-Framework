CREATE DATABASE liau;
USE liau;

CREATE TABLE `editoriales` (
  `edit_id` int(11) NOT NULL AUTO_INCREMENT,
  `edit_nombre` varchar(245) NOT NULL,
  PRIMARY KEY (`edit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `editoriales` VALUES (1,'Nombre modificado');
