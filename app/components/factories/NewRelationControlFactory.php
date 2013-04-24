<?php


class NewRelationRControlFactory implements INewRelationControlFactory {

	private $container;


	public function __construct(Nette\DI\Container $container) {
		$this->container = $container;
	}


	public function create(Model\Entity\Project $project) {
		return $this->container->createNewRelationControl($project);
	}

}
