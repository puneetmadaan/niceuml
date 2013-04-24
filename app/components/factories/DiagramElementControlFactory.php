<?php

use Nette\DI\Container;


class DiagramElementControlFactory implements IDiagramElementControlFactory {

	private $container;

	protected $types = array();


	public function __construct(Container $container) {
		$this->container = $container;
	}


	public function addType($type, $service) {
		$this->types[$type] = $service;
	}


	public function create(Model\Entity\Diagram $diagram, Model\Entity\Element $element) {
		if (isset($this->types[$element->type])) {
			$method = Container::getMethodName($this->types[$element->type], FALSE);
			return $this->container->$method($diagram, $element);
		}
		return $this->container->createDiagramElementControl($diagram, $element);
	}

}
