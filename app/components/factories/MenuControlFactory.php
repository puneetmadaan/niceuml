<?php


/** Creates menu control from DI container */
class MenuControlFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	/** @return MenuControl */
	public function create()
	{
		return $this->container->createMenuControl();
	}

}
