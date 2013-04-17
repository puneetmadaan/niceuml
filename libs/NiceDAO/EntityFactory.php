<?php


namespace NiceDAO;


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
		if ($table instanceof \Nette\Database\Table\Selection) {
			$name = $table->getName();
			$class = isset($this->classes[$name]) ? $this->classes[$name] : $this->defaultClass;
			$entity = new $class($data, $table);
		}
		else {
			$name = $table;
			$table = $this->emptyTables->get($name);
			$entity = $table->create($data);
		}
		return $entity;
	}

}
