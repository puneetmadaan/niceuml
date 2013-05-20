<?php

namespace Model;

use Nette;


/** User data access object */
class UserDAO extends BaseDAO
{


	/**
	 * @param  string
	 * @param  Entity\User to ignore
	 * @return Entity\User|FALSE
	 */
	public function getByLogin($login, Entity\User $except = NULL)
	{
		$table = $this->table()->where('login', $login);
		if ($except !== NULL)
			$table->where('id != ?', $except->id);
		return $table->fetch();
	}

}
