<?php

use Nette\DI\Container;


/** Creates relation control from DI container by type */
class RelationControlFactory
{

	private $container;

	protected $types = array();


	public function __construct(Container $container)
	{
		$this->container = $container;
	}


	/**
	 * @param string Element type
	 * @param string Service name
	 * @return void
	 */
	public function addType($type, $service)
	{
		$this->types[$type] = $service;
	}


	/**
	 * @param  string relation type
	 * @return IRelationControl
	 */
	public function create($type)
	{
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
