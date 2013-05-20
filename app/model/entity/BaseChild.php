<?php

namespace Model\Entity;


abstract class BaseChild extends BaseEntity
{

	/** @var Base */
	protected $parent;

	protected $parentFields = array();
	protected $parentMethods = array();


	public function getParent() {
		if ($this->parent === NULL) {
			$parent = $this->ref('id');
			$this->parent = $parent !== NULL ? $parent : FALSE;
		}
		return $this->parent ?: NULL;
	}


	public function setParent(BaseEntity $parent) {
		if ($this->parent === $parent)
			return $this;
		$this->parent = $parent;
		return $this;
	}


	public function update($data = NULL) {
		if ($this->parent)
			$this->parent->update();
		return parent::update($data);
	}


	public function delete() {
		$parent = $this->getParent();
		$result = parent::delete();
		if ($parent)
			$parent->delete();
		return $result;
	}


	public function & __get($name) {
		if (in_array($name, $this->parentFields))
			return $this->getParent()->__get($name);
		return parent::__get($name);
	}


	public function __set($name, $value) {
		if (in_array($name, $this->parentFields))
			$this->getParent()->__set($name, $value);
		else
			parent::__set($name, $value);
	}


	public function __isset($name) {
		if (in_array($name, $this->parentFields))
			return $this->getParent()->__isset($name);
		return parent::__isset($name);
	}


	public function __unset($name) {
		if (in_array($name, $this->parentFields))
			$this->getParent()->__unset($name);
		else
			parent::__unset($name);
	}


	public function __call($name, $args) {
		if (in_array($name, $this->parentMethods))
			call_user_method_array($name, $this->parent, $args);
		else
			return parent::__call($name, $args);
	}

}

