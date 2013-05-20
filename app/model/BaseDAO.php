<?php

namespace Model;

use Database\IEntityFactory,
	Model\Entity\BaseEntity,
	Nette,
	Nette\Database\Connection;


/** Base data access object */
class BaseDAO extends Nette\Object
{

	/** @var string */
	protected $tableName;

	/** @var Connection */
	protected $connection;

	/** @var IEntityFactory */
	protected $entityFactory;

	/** @var NewEntityTable */
	protected $newEntityTable;


	/** @param string */
	public function __construct($tableName, Connection $connection, Database\IEntityFactory $entityFactory)
	{
		$this->tableName = $tableName;
		$this->connection = $connection;
		$this->entityFactory = $entityFactory;
	}


	public function table()
	{
		return new Database\Selection($this->tableName, $this->connection, $this->entityFactory);
	}


	protected function getNewEntityTable()
	{
		if ($this->newEntityTable === NULL)
			$this->newEntityTable = new Database\NewEntityTable($this->tableName, $this->connection, $this->entityFactory);
		return $this->newEntityTable;
	}


	/**
	 * @param array|\Traversable|NULL
	 * @return Entity\BaseEntity
	 */
	public function create($data = NULL)
	{
		$entity = $this->getNewEntityTable()->create();
		if ($data !== NULL) {
			foreach ($data as $key => $value)
				$entity->$key = $value;
		}
		return $entity;
	}


	/**
	 * @param mixed primary key
	 * @return Entity\BaseEntity|NULL
	 */
	public function get($id)
	{
		return $this->table()->get($id);
	}


	/**
	 * @param Entity\BaseEntity
	 * @param array|\Traversable|NULL
	 * @return Entity\BaseEntity
	 */
	public function save(BaseEntity $entity = NULL, $data = NULL)
	{
		if ($entity === NULL)
			$entity = $this->create();

		if ($data !== NULL) {
			foreach ($data as $key => $value)
				$entity->$key = $value;
		}

		if ($this->isNew($entity))
			return $this->table()->insert($entity);

		$entity->update();
		return $entity;
	}


	/**
	 * @param Entity\BaseEntity
	 * @return int
	 */
	public function delete(BaseEntity $entity)
	{
		return $entity->delete();
	}


	/** @return bool */
	protected function isNew(BaseEntity $entity)
	{
		return $entity->getPrimary(FALSE) === NULL;
	}

}
