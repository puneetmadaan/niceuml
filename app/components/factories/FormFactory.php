<?php


/** Creates forms from DI container */
class FormFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	/** @return Nette\Application\UI\Form */
	public function create()
	{
		return $this->container->createForm();
	}

}
