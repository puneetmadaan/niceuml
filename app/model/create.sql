SET @OLD_CHECKS = @@FOREIGN_KEY_CHECKS;
SET FOREIGN_KEY_CHECKS = 0;


SET NAMES 'utf8';
SET storage_engine=InnoDB;


DROP TABLE IF EXISTS `core_diagram`;
CREATE TABLE `core_diagram` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`name` VARCHAR (50) NOT NULL,
	`project_id` INT UNSIGNED NOT NULL,
	`type` VARCHAR (50),
	FOREIGN KEY (`project_id`) REFERENCES `core_project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
	UNIQUE (`project_id`, `name`)
);


DROP TABLE IF EXISTS `core_element`;
CREATE TABLE `core_element` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`name` VARCHAR (50) NOT NULL,
	`project_id` INT UNSIGNED NOT NULL,
	`type` VARCHAR (50),
	FOREIGN KEY (`project_id`) REFERENCES `core_project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
	UNIQUE (`project_id`, `name`)
	-- package.project_id == project_id
);


DROP TABLE IF EXISTS `core_element_keyword`;
CREATE TABLE `core_element_keyword` (
	`element_id` INT UNSIGNED NOT NULL,
	`keyword_id` INT UNSIGNED NOT NULL,
	PRIMARY KEY (`element_id`, `keyword_id`),
	FOREIGN KEY (`element_id`) REFERENCES `core_element` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (`keyword_id`) REFERENCES `core_keyword` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
);


DROP TABLE IF EXISTS `core_keyword`;
CREATE TABLE `core_keyword` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`name` VARCHAR (50) NOT NULL UNIQUE
);


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
);


DROP TABLE IF EXISTS `core_project`;
CREATE TABLE `core_project` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`name` VARCHAR (50) NOT NULL
);


DROP TABLE IF EXISTS `core_relation`;
CREATE TABLE `core_relation` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`name` VARCHAR (50) NOT NULL,
	`start_id` INT UNSIGNED NOT NULL,
	`end_id` INT UNSIGNED NOT NULL,
	`type` VARCHAR (50),
	FOREIGN KEY (`start_id`) REFERENCES `core_element` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (`end_id`) REFERENCES `core_element` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
	-- start.project_id == end.project_id
);


DROP TABLE IF EXISTS `core_relation_keyword`;
CREATE TABLE `core_relation_keyword` (
	`relation_id` INT UNSIGNED NOT NULL,
	`keyword_id` INT UNSIGNED NOT NULL,
	PRIMARY KEY (`relation_id`, `keyword_id`),
	FOREIGN KEY (`relation_id`) REFERENCES `core_relation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (`keyword_id`) REFERENCES `core_keyword` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
);


DROP TABLE IF EXISTS `core_note`;
CREATE TABLE `core_note` (
	`id` INT UNSIGNED NOT NULL PRIMARY KEY,
	`text` TEXT NOT NULL,
	FOREIGN KEY (`id`) REFERENCES `core_element` (`id`)  ON DELETE CASCADE ON UPDATE CASCADE
);


SET FOREIGN_KEY_CHECKS = @OLD_CHECKS;
