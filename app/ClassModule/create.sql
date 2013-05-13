SET @OLD_CHECKS = @@FOREIGN_KEY_CHECKS;
SET FOREIGN_KEY_CHECKS = 0;


SET NAMES 'utf8';
SET storage_engine=InnoDB;



DROP TABLE IF EXISTS `class_class`;
CREATE TABLE `class_class` (
	`id` INT UNSIGNED NOT NULL PRIMARY KEY,
	`abstract` BOOLEAN NOT NULL,
	`static` BOOLEAN NOT NULL,
	FOREIGN KEY (`id`) REFERENCES `core_element` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);


DROP TABLE IF EXISTS `class_association`;
CREATE TABLE `class_association` (
	`id` INT UNSIGNED NOT NULL PRIMARY KEY,
	`direction` ENUM ('none', 'uni', 'bi') NOT NULL DEFAULT 'none',
	`sourceRole` VARCHAR (50) NOT NULL,
	`sourceMultiplicity` VARCHAR (10) NOT NULL,
	`targetRole` VARCHAR (50) NOT NULL,
	`targetMultiplicity` VARCHAR (10) NOT NULL,
	FOREIGN KEY (`id`) REFERENCES `core_relation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);


DROP TABLE IF EXISTS `class_attribute`;
CREATE TABLE `class_attribute` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`class_id` INT UNSIGNED NOT NULL,
	`visibility` ENUM ('public', 'protected', 'private', 'package') NOT NULL,
	`name` VARCHAR (50) NOT NULL,
	`type` VARCHAR (50),
	`multiplicity` VARCHAR(10) NOT NULL,
	`defaultValue` TEXT NOT NULL,
	`derived` BOOLEAN NOT NULL,
	`static` BOOLEAN NOT NULL,
	FOREIGN KEY (`class_id`) REFERENCES `class_class` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);


DROP TABLE IF EXISTS `class_operation`;
CREATE TABLE `class_operation` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`class_id` INT UNSIGNED NOT NULL,
	`visibility` ENUM ('public', 'protected', 'private', 'package') NOT NULL,
	`name` VARCHAR (50) NOT NULL,
	`returnType` VARCHAR (50),
	`abstract` BOOLEAN NOT NULL,
	`static` BOOLEAN NOT NULL,
	FOREIGN KEY (`class_id`) REFERENCES `class_class` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);


DROP TABLE IF EXISTS `class_operationParameter`;
CREATE TABLE `class_operationParameter` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`operation_id` INT UNSIGNED NOT NULL,
	`name` VARCHAR (50) NOT NULL,
	`type` VARCHAR (50),
	`multiplicity` VARCHAR(10) NOT NULL,
	`defaultValue` TEXT NOT NULL,
	`direction` ENUM ('in', 'out', 'inout') NOT NULL DEFAULT 'in',
	FOREIGN KEY (`operation_id`) REFERENCES `class_operation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);


SET FOREIGN_KEY_CHECKS = @OLD_CHECKS;
