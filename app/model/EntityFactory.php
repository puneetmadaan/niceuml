<?php


namespace Model;


class EntityFactory extends \Nette\Object {

	protected $classes;
	protected $defaultClass;
	protected $emptyTables;
	protected $newEntityMap;


	public function __construct(array $classes, $defaultClass, EmptyTableAccessor $emptyTables, NewEntityMap $newEntityMap) {
		$this->classes = $classes;
		$this->defaultClass = $defaultClass;
		$this->emptyTables = $emptyTables;
		$this->newEntityMap = $newEntityMap;
	}


	public function create($table, array $data = array()) {
		$new = !($table instanceof Selection);
		if ($table instanceof Selection)
			$name = $table->getName();
		else {
			$name = $table;
			$table = $this->emptyTables->get($name);
		}
		$class = isset($this->classes[$name]) ? $this->classes[$name] : $this->defaultClass;
		$entity = new $class($data, $table);
		if ($new)
			$this->newEntityMap->add($entity);
		else
			$this->newEntityMap->remove($entity);
		return $entity;
	}

}