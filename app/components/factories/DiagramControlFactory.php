<?php

use Nette\DI\Container;


class DiagramControlFactory {

	private $container;

	protected $types = array();


	public function __construct(Container $container) {
		$this->container = $container;
	}


	public function addType($type, $service) {
		$this->types[$type] = $service;
	}


	public function has($type)
	{
		return array_key_exists($type, $this->types);
	}


	public function create($type) {
		if (isset($this->types[$type])) {
			$method = Container::getMethodName($this->types[$type], FALSE);
			return $this->container->$method($type);
		}
		return NULL;
	}

}
