<?php

namespace Model;

use Database\IEntityFactory,
	Model\Entity\BaseEntity,
	Nette,
	Nette\Database\Connection;


class BaseDAO extends Nette\Object {

	/** @var string */
	protected $tableName;

	/** @var Connection */
	protected $connection;

	/** @var IEntityFactory */
	protected $entityFactory;

	/** @var NewEntityTable */
	protected $newEntityTable;


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


	public function create($data = NULL)
	{
		$entity = $this->getNewEntityTable()->create();
		if ($data !== NULL) {
			foreach ($data as $key => $value)
				$entity->$key = $value;
		}
		return $entity;
	}


	public function get($id)
	{
		return $this->table()->get($id);
	}


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


	public function delete(BaseEntity $entity)
	{
		return $entity->delete();
	}


	protected function isNew(BaseEntity $entity)
	{
		return $entity->getPrimary(FALSE) === NULL;
	}


	public function beginTransaction()
	{
		$this->connection->beginTransaction();
	}


	public function commit()
	{
		$this->connection->commit();
	}


	public function rollBack()
	{
		$this->connection->rollBack();
	}

}
