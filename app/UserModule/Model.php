<?php

namespace UserModule;


use Model\Service;


/*

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(20) NOT NULL,
    `surname` varchar(30) NOT NULL,
    `nick` varchar(20) NOT NULL,
    `email` varchar(100) NOT NULL,
    `login` varchar(100) NOT NULL,
    `password` varchar(60) NOT NULL,
    `passwordNew` varchar(60),
    `passwordNewCode` varchar(10),
    PRIMARY KEY (`id`),
    UNIQUE KEY `login` (`login`),
    UNIQUE KEY `passwordNewCode` (`passwordNewCode`)
);

*/


class Model extends Service {
    
    public function isLoginUnique($login, User $oldUser = NULL) {
        $table = $this->table()->where('login', $login);
        if ($oldUser !== NULL)
            $table->where('id != ?', $oldUser->id);
        return $table->fetch() ? FALSE : TRUE;
    }

}
