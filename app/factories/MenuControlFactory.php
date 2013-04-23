<?php


class MenuControlFactory implements IMenuControlFactory {

	private $container;


	public function __construct(Nette\DI\Container $container) {
		$this->container = $container;
	}


	public function create() {
		return $this->container->createMenuControl();
	}

}
