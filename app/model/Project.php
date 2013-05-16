<?php

namespace Model;

use Nette;


class Project extends Base
{


	public function findByUserId($id)
	{
		return $this->table()->where('user_project:user_id', (int) $id);
	}

}
