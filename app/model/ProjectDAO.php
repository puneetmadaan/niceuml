<?php

namespace Model;

use Nette;

class ProjectDAO extends BaseDAO
{


	public function findByUserId($id)
	{
		return $this->table()->where('user_project:user_id', (int) $id);
	}

}
