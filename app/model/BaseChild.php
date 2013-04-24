<?php

namespace Model;


class BaseChild extends Base {

	protected $parentModel;

	public function __construct($tableName, Connection $connection, IEntityFactory $entityFactory, Base $parent) {
		parent::__construct($tableName, $connection, $entityFactory);
		$this->parentModel = $parent;
	}


	public function create($data = NULL) {
		$entity = parent::create();
		if ($entity instanceof Entity\BaseChild)
			$entity->setParent($parentModel->create());
		if ($data !== NULL) {
			foreach ($data as $key => $value)
				$entity->$key = $value;
		}
		return $entity;
	}


	public function save(Entity $entity = NULL, $data = NULL) {
		if ($entity === NULL)
			$entity = $this->create();

		if ($data !== NULL) {
			foreach ($data as $key => $value)
				$entity->$key = $value;
		}

		if ($this->isNew($entity)) {
			if ($entity instanceof Entity\BaseChild) {
				$parent = $this->parentModel->save($entity->getParent());
				$entity->id = $parent->id;
				return $this->table()->insert($entity)->setParent($parent);
			}
			return $this->table()->insert($entity);
		}

		$entity->update();
		return $entity;
	}

}
