<?php

namespace Model;


/** ProjectDAO data access object */
class ProjectDAO extends BaseDAO
{


	/**
	 * @param  int
	 * @return Database\Selection
	 */
	public function findByUserId($id)
	{
		return $this->table()->where('user_project:user_id', (int) $id);
	}

}
