<?php

namespace Model\Security;

use Model\Entity\BaseEntity,
	Nette,
	Nette\Security;


/** Authorizator resource for entities */
class EntityResource extends Nette\Object implements Security\IResource
{

	/** @var BaseEntity */
	protected $entity;


	public function __construct(BaseEntity $entity) {
		$this->entity = $entity;
	}


	/** @return BaseEntity */
	public function getEntity() {
		return $this->entity;
	}


	/** @return string */
	public function getResourceId() {
		$name = $this->entity->table->name;
		$pos = strpos($name, '_');
		return $pos === FALSE ? $name : substr($name, $pos + 1);
	}

}
