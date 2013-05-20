<?php


/** Creates project tree control from DI container */
class ProjectTreeControlFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	/** @return ProjectTreeControl */
	public function create()
	{
		return $this->container->createProjectTreeControl();
	}

}
