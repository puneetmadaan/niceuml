<?php


namespace Model;


class EmptyTableAccessor extends \Nette\Object {

	protected $factory;
	protected $tables = array();

	public function __construct(\Nette\Callback $factory) {
		$this->factory = $factory;
	}


	public function & __get($name) {
		return get($name);
	}


	public function get($name) {
		return isset($this->tables[$name]) ?
			$this->tables[$name] :
			$this->tables[$name] = $this->factory->invoke($name);
	}

}