<?php

use Nette\DI\Container;


class DiagramControlFactory implements IDiagramControlFactory {

	private $container;

	protected $types = array();


	public function __construct(Container $container) {
		$this->container = $container;
	}


	public function addType($type, $service) {
		$this->types[$type] = $service;
	}


	public function create(Model\Entity\Diagram $diagram) {
		if (isset($this->types[$diagram->type])) {
			$method = Container::getMethodName($this->types[$diagram->type], FALSE);
			return $this->container->$method($diagram);
		}
		return $this->container->createDiagramControl($diagram);
	}

}
