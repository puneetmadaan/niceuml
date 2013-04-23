<?php


class SourceControlFactory implements ISourceControlFactory {

	private $container;


	public function __construct(Nette\DI\Container $container) {
		$this->container = $container;
	}


	public function create(Model\Entity\Project $project) {
		return $this->container->createSourceControl($project);
	}

}
