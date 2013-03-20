<?php


namespace Model;


class ServiceDependencies {

	public function __construct(\Nette\Callback $tableFactory, EntityFactory $entityFactory, NewEntityMap $newEntityMap) {
		$this->tableFactory = $tableFactory;
		$this->entityFactory = $entityFactory;
		$this->newEntityMap = $newEntityMap;
	}

}