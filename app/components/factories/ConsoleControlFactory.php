<?php


/** Creates console control from DI container */
class ConsoleControlFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	/** @return ConsoleControl */
	public function create()
	{
		return $this->container->createConsoleControl();
	}

}
