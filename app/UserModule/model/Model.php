<?php

namespace UserModule;

use Model\Base;


class Model extends Base {

	public function isLoginUnique($login, User $oldUser = NULL) {
		$table = $this->table()->where('login', $login);
		if ($oldUser !== NULL)
			$table->where('id != ?', $oldUser->id);
		return $table->fetch() ? FALSE : TRUE;
	}

}
