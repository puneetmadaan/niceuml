<?php


/** Creates source control form DI container */
class SourceControlFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	/** @return SourceControl */
	public function create()
	{
		return $this->container->createSourceControl();
	}

}
