<?php

namespace Model;

use NiceDAO\Service;
use Nette\Security\User;
use NiceDAO\Selection;


class Base extends Service {
	
	public function filterAllowed(User $user, Selection $table, $action = NULL) {
	}

}
