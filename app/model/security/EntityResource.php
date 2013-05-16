<?php

namespace Model\Security;

use Model\Entity\Base as BaseEntity,
	Nette,
	Nette\Security;


class EntityResource extends Nette\Object implements Security\IResource {

	protected $entity;


	public function __construct(BaseEntity $entity) {
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
