DROP DATABASE IF EXISTS niceuml_test;
CREATE DATABASE IF NOT EXISTS niceuml_test;
USE niceuml_test;

SET @OLD_CHECKS = @@FOREIGN_KEY_CHECKS;
SET FOREIGN_KEY_CHECKS = 0;


DROP TABLE IF EXISTS `core_diagram`;
CREATE TABLE `core_diagram` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`name` VARCHAR (50) NOT NULL,
	`project_id` INT UNSIGNED NOT NULL,
	`type` VARCHAR (50) NOT NULL,
	FOREIGN KEY (`project_id`) REFERENCES `core_project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
	UNIQUE (`project_id`, `name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `core_element`;
CREATE TABLE `core_element` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`name` VARCHAR (50) NOT NULL,
	`project_id` INT UNSIGNED NOT NULL,
	`type` VARCHAR (50) NOT NULL,
	FOREIGN KEY (`project_id`) REFERENCES `core_project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
	UNIQUE (`project_id`, `name`)
	-- package.project_id == project_id
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `core_placement`;
CREATE TABLE `core_placement` (
	`diagram_id` INT UNSIGNED NOT NULL,
	`element_id` INT UNSIGNED NOT NULL,
	`posX` INT UNSIGNED NOT NULL,
	`posY` INT UNSIGNED NOT NULL,
	PRIMARY KEY (`diagram_id`, `element_id`),
	FOREIGN KEY (`diagram_id`) REFERENCES `core_diagram` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (`element_id`) REFERENCES `core_element` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
	-- diagram.project_id == element.project_id
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `core_project`;
CREATE TABLE `core_project` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`name` VARCHAR (50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `core_relation`;
CREATE TABLE `core_relation` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`name` VARCHAR (50) NOT NULL,
	`start_id` INT UNSIGNED NOT NULL,
	`end_id` INT UNSIGNED NOT NULL,
	`type` VARCHAR (50) NOT NULL,
	FOREIGN KEY (`start_id`) REFERENCES `core_element` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (`end_id`) REFERENCES `core_element` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
	-- start.project_id == end.project_id
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `core_note`;
CREATE TABLE `core_note` (
	`id` INT UNSIGNED NOT NULL PRIMARY KEY,
	`text` TEXT NOT NULL,
	FOREIGN KEY (`id`) REFERENCES `core_element` (`id`)  ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `core_user`;
CREATE TABLE `core_user` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(20) NOT NULL,
	`surname` varchar(30) NOT NULL,
	`email` varchar(100) NOT NULL,
	`role` ENUM('user', 'admin') NOT NULL DEFAULT 'user',
	`login` varchar(100) NOT NULL,
	`password` varchar(60) NOT NULL,
	`passwordNew` varchar(60),
	`passwordNewCode` varchar(10),
	PRIMARY KEY (`id`),
	UNIQUE KEY `login` (`login`),
	UNIQUE KEY `passwordNewCode` (`passwordNewCode`)
);


DROP TABLE IF EXISTS `core_user_project`;
CREATE TABLE `core_user_project` (
	`user_id` int(10) unsigned NOT NULL,
	`project_id` int(10) unsigned NOT NULL,
	`role` ENUM('user', 'owner') NOT NULL DEFAULT 'user',
	PRIMARY KEY (`user_id`, `project_id`),
	FOREIGN KEY (`user_id`) REFERENCES `core_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (`project_id`) REFERENCES `core_project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);


-- default administrator is administrator => password
INSERT INTO `core_user` (`id`, `name`, `surname`, `email`, `role`, `login`, `password`) VALUES
	(1, 'Admin', 'Administrator', '', 'admin', 'administrator', '$2a$07$6ms4hohgckr4nalkmzafee2CoNBySHhbjqCXVxQq1VcJuVWokttYW');




DROP TABLE IF EXISTS `class_class`;
CREATE TABLE `class_class` (
	`id` INT UNSIGNED NOT NULL PRIMARY KEY,
	`abstract` BOOLEAN NOT NULL,
	`static` BOOLEAN NOT NULL,
	FOREIGN KEY (`id`) REFERENCES `core_element` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `class_association`;
CREATE TABLE `class_association` (
	`id` INT UNSIGNED NOT NULL PRIMARY KEY,
	`direction` ENUM ('none', 'uni', 'bi') NOT NULL DEFAULT 'none',
	`sourceRole` VARCHAR (50) NOT NULL,
	`sourceMultiplicity` VARCHAR (10) NOT NULL,
	`targetRole` VARCHAR (50) NOT NULL,
	`targetMultiplicity` VARCHAR (10) NOT NULL,
	FOREIGN KEY (`id`) REFERENCES `core_relation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


SET FOREIGN_KEY_CHECKS = @OLD_CHECKS;


INSERT INTO `core_project` (`id`, `name`) VALUES
	(1, 'Project');

INSERT INTO `core_user_project` (`user_id`, `project_id`) VALUES
	(1, 1);

INSERT INTO `core_element` (`id`, `name`, `project_id`, `type`) VALUES
	(1, 'Class 1', 1, 'class'),
	(2, 'Class 2', 1, 'class'),
	(3, 'Note', 1, 'note'),
	(4, 'Dolor', 1, 'class'),
	(5, 'Sit', 1, 'class');

INSERT INTO `class_class` (`id`) VALUES
	(1), (2), (4), (5);

INSERT INTO `core_note` (`id`, `text`) VALUES
	(3, "Lorem ipsum
Dolor sit amet");

INSERT INTO `core_diagram` (`id`, `name`, `project_id`, `type`) VALUES
	(1, 'Class diagram', 1, 'class');

INSERT INTO `core_placement` (`diagram_id`, `element_id`, `posX`, `posY`) VALUES
	(1, 1, 0, 100),
	(1, 2, 550, 0),
	(1, 3, 250, 250),
	(1, 4, 500, 150),
	(1, 5, 500, 250);

INSERT INTO `core_relation` (`id`, `name`, `start_id`, `end_id`, `type`) VALUES
	(1, '!!!', 3, 1, 'noteLink'),
	(2, '???', 3, 2, 'noteLink'),
	(3, 'is cool with', 1, 2, 'association'),
	(4, '', 2, 4, 'association'),
	(5, '', 4, 5, 'association'),
	(6, '', 5, 1, 'association'),
	(7, 'self', 4, 4, 'association');

INSERT INTO `class_association` (`id`, `direction`, `sourceRole`, `sourceMultiplicity`, `targetRole`, `targetMultiplicity`) VALUES
	(3, 'none', 'foo', '1..*', 'bar', '0..1'),
	(4, 'uni', '', '', '', ''),
	(5, 'bi', '', '', '', ''),
	(6, 'uni', '', '', '', ''),
	(7, 'bi', '', '', '', '');


INSERT INTO `class_attribute` (`id`, `class_id`, `visibility`, `name`, `type`, `multiplicity`, `defaultValue`, `derived`, `static`) VALUES
	(1, 2, 'public', 'setFoo', '', '0..1', 'null', 0, 0),
	(2, 2, 'protected', 'getFoo', 'Foo', '', '""', 1, 0),
	(3, 2, 'private', 'food', '', '', '', 0, 1),
	(4, 2, 'package', 'drink', '', '', '', 1, 1);
