<?php

namespace Model;

use NiceDAO\Entity,
	NiceDAO\Selection,
	NiceDAO\Service,
	Nette\Security\User;


class Base extends Service {

	/** @var array of function(Entity $entity) */
	public $onSave = array();

	/** @var array of function(Entity $entity) */
	public $onCreate = array();

	/** @var array of function(Entity $entity) */
	public $onUpdate = array();

	/** @var array of function(Entity $entity) */
	public $onDelete = array();


	public function save(Entity $entity = NULL, $data = NULL) {
		$new = $entity === NULL || $this->isNew($entity);
		$entity = parent::save($entity, $data);

		if ($new)
			$this->onCreate($entity);
		else
			$this->onUpdate($entity);

		$this->onSave($entity);
		return $entity;
	}


	public function delete(Entity $entity) {
		$return = parent::delete($entity);
		$this->onDelete($entity);
		return $return;
	}


	public function filterAllowed(User $user, Selection $table, $action = NULL) {
	}

}
