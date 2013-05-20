<?php

use Nette\DI\Container;


/** Creates diagram from DI container by type */
class DiagramControlFactory
{

	private $container;

	protected $types = array();


	public function __construct(Container $container)
	{
		$this->container = $container;
	}


	/**
	 * @param string Diagram type
	 * @param string Service name
	 * @return void
	 */
	public function addType($type, $service)
	{
		$this->types[$type] = $service;
	}


	/**
	 * @param string Diagram type
	 * @return boolean
	 */
	public function has($type)
	{
		return array_key_exists($type, $this->types);
	}


	/**
	 * @param  string Diagram type
	 * @return IDiagramControl
	 */
	public function create($type)
	{
		if (isset($this->types[$type])) {
			$method = Container::getMethodName($this->types[$type], FALSE);
			$control = $this->container->$method();
			if (!$control instanceof IDiagramControl)
				throw new Nette\UnexpectedValueException('Factory did not return expected IDiagramControl.');
			return $control;
		}
		return NULL;
	}

}
