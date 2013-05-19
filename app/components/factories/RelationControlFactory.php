<?php

use Nette\DI\Container;


class RelationControlFactory {

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
			$control = $this->container->$method();
		}
		else
			$control = $this->container->createRelationControl();
		if (!$control instanceof IRelationControl)
			throw new Nette\UnexpectedValueException('Factory did not return expected IRelationControl.');
		return $control;
	}

}
