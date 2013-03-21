<?php

namespace ProjectModule;


use Model\Service;


/*

DROP TABLE IF EXISTS `project`;
CREATE TABLE `project` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(50) NOT NULL,
    `user_id` int(10) unsigned NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
);


CREATE TABLE `user_project` (
    `user_id` int(10) unsigned NOT NULL,
    `project_id` int(10) unsigned NOT NULL,
    PRIMARY KEY (`user_id`, `project_id`),
    FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);


*/


class Model extends Service {

}
