<?php

use Nette\DI\Container;


/** Creates element control from DI container by type */
class ElementControlFactory
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
	 */
	public function addType($type, $service)
	{
		$this->types[$type] = $service;
	}


	/**
	 * @param  string Element type
	 * @return IElementControl
	 */
	public function create($type)
	{
		if (isset($this->types[$type])) {
			$method = Container::getMethodName($this->types[$type], FALSE);
			$control = $this->container->$method();
		}
		else
			$control = $this->container->createElementControl();
		if (!$control instanceof IElementControl)
			throw new Nette\UnexpectedValueException('Factory did not return expected IElementControl.');
		return $control;
	}

}
