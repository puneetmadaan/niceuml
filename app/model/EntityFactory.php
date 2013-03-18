<?php


namespace Model;


class EntityFactory extends \Nette\Object {

	protected $classes;
	protected $defaultClass;


	public function __construct(array $classes, $defaultClass) {
		$this->classes = $classes;
		$this->defaultClass = $defaultClass;
	}


	public function create(array $data, Selection $table) {
		$name = $table->getName();
		$class = isset($this->classes[$name]) ? $this->classes[$name] : $this->defaultClass;
		return new $class($data, $table);
	}

}