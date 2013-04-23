<?php

namespace Model;

use NiceDAO\Selection,
	Nette\Security\User;


class Project extends Base {


	public function filterAllowed(User $user, Selection $table, $action = NULL) {
		$table->where('user_user_project:user_id', $user->id);
	}

}
