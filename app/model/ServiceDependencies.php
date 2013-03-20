<?php


namespace Model;


class ServiceDependencies {

	public function __construct(\Nette\Callback $tableFactory, EntityFactory $entityFactory) {
		$this->tableFactory = $tableFactory;
		$this->entityFactory = $entityFactory;
	}

}