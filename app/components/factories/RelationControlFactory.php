<?php

use Nette\DI\Container;


class RelationControlFactory implements IRelationControlFactory {

	private $container;

	protected $types = array();


	public function __construct(Container $container) {
		$this->container = $container;
	}


	public function addType($type, $service) {
		$this->types[$type] = $service;
	}


	public function create(Model\Entity\Relation $relation) {
		if (isset($this->types[$relation->type])) {
			$method = Container::getMethodName($this->types[$relation->type], FALSE);
			return $this->container->$method($relation);
		}
		return $this->container->createRelationControl($relation);
	}

}
