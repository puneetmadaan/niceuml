<?php

namespace Model\Entity;

use Nette;


class Project extends Base
{


	/**
	 * @return Selection
	 */
	public function getUsers()
	{
		return $this->related('user_project');
	}


	/**
	 * @param int
	 * @return string|FALSE
	 */
	public function getUserRole($id)
	{
		$row = $this->getUsers()->where('user_id', (int) $id)->fetch();
		return $row ? $row->role : FALSE;
	}


	/**
	 * @param int
	 * @return bool
	 */
	public function addUser($id, $owner = FALSE)
	{
		if ($this->getUserRole($id))
			return FALSE;
		return $this->getUsers()->insert(array(
			'user_id' => $id,
			'role' => $owner ? 'owner' : 'user',
		));
	}


	/**
	 * @param int
	 * @return bool
	 */
	public function removeUser($id)
	{
		$role = $this->getUserRole($id);
		if (!$role || $role === 'owner')
			return FALSE;
		return $this->getUsers()->where('user_id', $id)->delete();
	}

}
