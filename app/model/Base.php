<?php

namespace Model;

use NiceDAO\Entity,
	NiceDAO\Selection,
	NiceDAO\Service,
	Nette\Security\User;


class Base extends Service implements IModel, ISourceModel {

	/** @var array of function(Entity $entity) */
	public $onSave = array();

	/** @var array of function(Entity $entity) */
	public $onCreate = array();

	/** @var array of function(Entity $entity) */
	public $onUpdate = array();

	/** @var array of function(Entity $entity) */
	public $onDelete = array();


	public function save($entity = NULL, $data = NULL) {
		$new = $entity === NULL || $this->isNew($entity);
		$entity = parent::save($entity, $data);

		if ($new)
			$this->onCreate($entity);
		else
			$this->onUpdate($entity);

		$this->onSave($entity);
		return $entity;
	}


	public function delete($entity) {
		$return = parent::delete($entity);
		$this->onDelete($entity);
		return $return;
	}


	public function filterAllowed(User $user, Selection $table, $action = NULL) {
	}


	public function loadSource($source, $project = NULL) {
		if (isset($source['id']))
			$entity = $this->get($source['id']);
		else
			$entity = $this->create();
		if (isset($project))
			$entity->project_id = $project->id;
		return $this->save($entity, $source);
	}

	public function dumpSource($entity) {
		return $entity->toArray();
	}
}
