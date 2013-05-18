<?php

namespace Model;

use Model\Database\IEntityFactory,
	Nette\Database\Connection;


class BaseChildDAO extends BaseDAO {

	protected $parentModel;

	public function __construct($tableName, Connection $connection, IEntityFactory $entityFactory, BaseDAO $parent) {
		parent::__construct($tableName, $connection, $entityFactory);
		$this->parentModel = $parent;
	}


	public function getByParent(Entity\BaseEntity $parent) {
		$row = $parent->ref($this->tableName, 'id');
		if ($row)
			$row->setParent($parent);
		return $row;
	}


	public function create($data = NULL) {
		$entity = parent::create();
		if ($entity instanceof Entity\BaseChild)
			$entity->setParent($this->parentModel->create());
		if ($data !== NULL) {
			foreach ($data as $key => $value)
				$entity->$key = $value;
		}
		return $entity;
	}


	public function save(Entity\BaseEntity $entity = NULL, $data = NULL) {
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
