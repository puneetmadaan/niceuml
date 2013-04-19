<?php

namespace NiceDAO;

use Nette\Database\Table;


class EntityFactory extends \Nette\Object {

	protected $classes;
	protected $defaultClass;
	protected $emptyTables;


	public function __construct(array $classes, $defaultClass) {
		$this->classes = $classes;
		$this->defaultClass = $defaultClass;
	}


	public function create(Table\Selection $table, array $data = array()) {
		$name = $table->getName();
		$class = isset($this->classes[$name]) ? $this->classes[$name] : $this->defaultClass;
		$entity = new $class($data, $table);
		return $entity;
	}

}
