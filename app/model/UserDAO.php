<?php

namespace Model;


class UserDAO extends Base
{


	public function getByLogin($login, Entity\User $except = NULL) {
		$table = $this->table()->where('login', $login);
		if ($except !== NULL)
			$table->where('id != ?', $except->id);
		return $table->fetch();
	}

}
