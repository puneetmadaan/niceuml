<?php

namespace Model;

use Model\Database\IEntityFactory,
	Nette\Database\Connection;


/** Base data access object for child tables */
class BaseChildDAO extends BaseDAO
{

	/** @var BaseDAO */
	protected $parentModel;


	/** @param string */
	public function __construct($tableName, Connection $connection, IEntityFactory $entityFactory, BaseDAO $parent)
	{
		parent::__construct($tableName, $connection, $entityFactory);
		$this->parentModel = $parent;
	}


	/** @return Entity\BaseEntity|NULL */
	public function getByParent(Entity\BaseEntity $parent)
	{
		$row = $parent->ref($this->tableName, 'id');
		if ($row)
			$row->setParent($parent);
		return $row;
	}


	/**
	 * @param array|\Traversable|NULL
	 * @return Entity\BaseEntity
	 */
	public function create($data = NULL)
	{
		$entity = parent::create();
		if ($entity instanceof Entity\BaseChild)
			$entity->setParent($this->parentModel->create());
		if ($data !== NULL) {
			foreach ($data as $key => $value)
				$entity->$key = $value;
		}
		return $entity;
	}


	/**
	 * @param Entity\BaseEntity
	 * @param array|\Traversable|NULL
	 * @return Entity\BaseEntity
	 */
	public function save(Entity\BaseEntity $entity = NULL, $data = NULL)
	{
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
