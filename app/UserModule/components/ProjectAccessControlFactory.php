<?php

namespace UserModule;


class ProjectAccessControlFactory implements \IProjectAccessControlFactory {

	private $container;


	public function __construct(\Nette\DI\Container $container) {
		$this->container = $container;
	}


	public function create(\Model\Entity\Project $project) {
		return $this->container->createUserModule__projectAccessControl($project);
	}

}
