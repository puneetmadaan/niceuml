<?php


class FormFactory {

	private $container;


	public function __construct(Nette\DI\Container $container) {
		$this->container = $container;
	}


	public function create() {
		return $this->container->createForm();
	}

}
