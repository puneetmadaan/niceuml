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


-- DROP TABLE IF EXISTS `core_element_keyword`;
-- CREATE TABLE `core_element_keyword` (
-- 	`element_id` INT UNSIGNED NOT NULL,
-- 	`keyword_id` INT UNSIGNED NOT NULL,
-- 	PRIMARY KEY (`element_id`, `keyword_id`),
-- 	FOREIGN KEY (`element_id`) REFERENCES `core_element` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
-- 	FOREIGN KEY (`keyword_id`) REFERENCES `core_keyword` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


-- DROP TABLE IF EXISTS `core_keyword`;
-- CREATE TABLE `core_keyword` (
-- 	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
-- 	`name` VARCHAR (50) NOT NULL UNIQUE
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


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


-- DROP TABLE IF EXISTS `core_relation_keyword`;
-- CREATE TABLE `core_relation_keyword` (
-- 	`relation_id` INT UNSIGNED NOT NULL,
-- 	`keyword_id` INT UNSIGNED NOT NULL,
-- 	PRIMARY KEY (`relation_id`, `keyword_id`),
-- 	FOREIGN KEY (`relation_id`) REFERENCES `core_relation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
-- 	FOREIGN KEY (`keyword_id`) REFERENCES `core_keyword` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `core_user_project`;
CREATE TABLE `core_user_project` (
	`user_id` int(10) unsigned NOT NULL,
	`project_id` int(10) unsigned NOT NULL,
	`role` ENUM('user', 'owner') NOT NULL DEFAULT 'user',
	PRIMARY KEY (`user_id`, `project_id`),
	FOREIGN KEY (`user_id`) REFERENCES `core_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (`project_id`) REFERENCES `core_project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


SET FOREIGN_KEY_CHECKS = @OLD_CHECKS;


-- default administrator is administrator => password
INSERT INTO `core_user` (`id`, `name`, `surname`, `email`, `role`, `login`, `password`) VALUES
	(1, 'Admin', 'Administrator', '', 'admin', 'administrator', '$2a$07$6ms4hohgckr4nalkmzafee2CoNBySHhbjqCXVxQq1VcJuVWokttYW');
