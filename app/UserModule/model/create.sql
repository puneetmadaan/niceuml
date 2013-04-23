SET @OLD_CHECKS = @@FOREIGN_KEY_CHECKS;
SET FOREIGN_KEY_CHECKS = 0;


SET NAMES 'utf8';


DROP TABLE IF EXISTS `user_user`;
CREATE TABLE `user_user` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(20) NOT NULL,
	`surname` varchar(30) NOT NULL,
	`nick` varchar(20) NOT NULL,
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


DROP TABLE IF EXISTS `user_user_project`;
CREATE TABLE `user_user_project` (
	`user_id` int(10) unsigned NOT NULL,
	`project_id` int(10) unsigned NOT NULL,
	`role` ENUM('user', 'admin', 'owner') NOT NULL DEFAULT 'user',
	PRIMARY KEY (`user_id`, `project_id`),
	FOREIGN KEY (`user_id`) REFERENCES `user_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (`project_id`) REFERENCES `core_project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);


SET FOREIGN_KEY_CHECKS = @OLD_CHECKS;
