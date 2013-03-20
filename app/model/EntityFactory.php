<?php


namespace Model;


class EntityFactory extends \Nette\Object {

	protected $classes;
	protected $defaultClass;
	protected $emptyTables;


	public function __construct(array $classes, $defaultClass, EmptyTableAccessor $emptyTables) {
		$this->classes = $classes;
		$this->defaultClass = $defaultClass;
		$this->emptyTables = $emptyTables;
	}


	public function create($table, array $data = array()) {
		if ($table instanceof Selection)
			$name = $table->getName();
		else {
			$name = $table;
			$table = $this->emptyTables->get($name);
		}
		$class = isset($this->classes[$name]) ? $this->classes[$name] : $this->defaultClass;
		$entity = new $class($data, $table);
		return $entity;
	}

}