<?php

use Nette\DI\Container;


class ElementControlFactory {

	private $container;

	protected $types = array();


	public function __construct(Container $container) {
		$this->container = $container;
	}


	public function addType($type, $service) {
		$this->types[$type] = $service;
	}


	public function create($type) {
		if (isset($this->types[$type])) {
			$method = Container::getMethodName($this->types[$type], FALSE);
			return $this->container->$method();
		}
		return $this->container->createElementControl();
	}

}
