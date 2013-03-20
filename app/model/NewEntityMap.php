<?php

namespace Model;


class NewEntityMap extends \Nette\Object {


	protected $hashes = array();


	public function add(Entity $entity) {
		$this->hashes[spl_object_hash($entity)] = TRUE;
		return $this;
	}


	public function remove(Entity $entity) {
		unset ($this->hashes[spl_object_hash($entity)]);
		return $this;
	}


	public function isNew(Entity $entity) {
		return !empty($this->hashes[spl_object_hash($entity)]);
	}


}