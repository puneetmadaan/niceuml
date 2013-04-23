<?php


namespace UserModule;


use Nette\Security;
use Model\Entity;


class EntityResource extends \Nette\Object implements Security\IResource {

	protected $entity;

	public function __construct(Entity\Base $entity) {
		$this->entity = $entity;
	}


	public function getEntity() {
		return $this->entity;
	}


	public function getResourceId() {
		$name = $this->entity->table->name;
		$pos = strpos($name, '_');
		return $pos === FALSE ? $name : substr($name, $pos + 1);
	}

}
