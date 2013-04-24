<?php


class NewRelationControlFactory implements INewRelationControlFactory {

	private $container;


	public function __construct(Nette\DI\Container $container) {
		$this->container = $container;
	}


	public function create(Model\Entity\Element $element) {
		return $this->container->createNewRelationControl($element);
	}

}
