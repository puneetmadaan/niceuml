<?php


namespace UserModule;


use Nette\Security;
use Model\Entity;


class EntityResource extends \Nette\Object implements Security\IResource {
	
	protected $entity;

	public function __construct(Entity $entity) {
		$this->entity = $entity;
	}


	public function getEntity() {
		return $this->entity;
	}


	public function getResourceId() {
		return $this->entity->table->name;
	}

}