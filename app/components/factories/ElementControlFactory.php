<?php


class ElementControlFactory implements IElementControlFactory {

	private $container;


	public function __construct(Nette\DI\Container $container) {
		$this->container = $container;
	}


	public function create(Model\Entity\Element $element) {
		return $this->container->createElementControl($element);
	}

}
