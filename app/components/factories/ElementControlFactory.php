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


	public function create(Model\Entity\Element $element) {
		if (isset($this->types[$element->type])) {
			$method = Container::getMethodName($this->types[$element->type], FALSE);
			return $this->container->$method($element);
		}
		return $this->container->createElementControl($element);
	}

}
