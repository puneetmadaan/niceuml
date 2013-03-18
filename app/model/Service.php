<?php


namespace Model;


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


	public function create(array $data = array()) {
		return $this->table()->insert($data);
	}


	public function get($id) {
		return $this->table()->get($id);
	}


	public function save(Entity $entity, $data = NULL) {
		return $entity->update($data);
	}


	public function delete(Entity $entity) {
		return $entity->delete($data);
	}


}