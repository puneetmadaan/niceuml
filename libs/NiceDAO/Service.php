<?php


namespace NiceDAO;


class Service extends \Nette\Object {

	/** @var string */
	protected $tableName;

	/** @var Nette\Callback($name) */
	protected $tableFactory;

	/** @var EntityFactory */
	protected $entityFactory;


	public function __construct($tableName, ServiceDependencies $sd) {
		$this->tableName = $tableName;
		$this->tableFactory = $sd->tableFactory;
		$this->entityFactory = $sd->entityFactory;
	}


	public function table() {
		return $this->tableFactory->invoke($this->tableName);
	}


	public function create($data = NULL) {
		$entity = $this->entityFactory->create($this->tableName);
		if ($data !== NULL) {
			foreach ($data as $key => $value)
				$entity->$key = $value;
		}
		return $entity;
	}


	public function get($id) {
		return $this->table()->get($id);
	}


	public function save(Entity $entity = NULL, $data = NULL) {
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


	public function delete(Entity $entity) {
		return $entity->delete();
	}


	protected function isNew(Entity $entity) {
		return $entity->getPrimary(FALSE) === NULL;
	}

}
