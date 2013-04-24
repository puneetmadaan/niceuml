<?php

namespace Model\Entity;


class ElementChild extends Base {

	/** @var Element */
	protected $parent;


	public function getParent() {
		if ($this->parent === NULL) {
			$parent = $this->ref('core_element', 'id');
			$this->parent = $parent === NULL ? $parent : FALSE;
			if ($this->parent)
				$parent->setChild($this);
		}
		return $this->parent ?: NULL;
	}


	public function setParent(Element $parent) {
		if ($this->parent === $parent)
			return $this;
		if ($this->parent !== NULL)
			throw new \Nette\InvalidStateException;
		$this->parent = $parent;
		$this->parent->setChild($this);
		return $this;
	}


	public function getName() {
		return $this->getParent()->name;
	}


	public function setName($value) {
		$this->getParent()->name = $value;
		return $this;
	}


	public function getProject() {
		return $this->getParent()->project;
	}


	public function setProject(Project $value) {
		$this->getParent()->project = $value;
		return $this;
	}


	public function getProject_id() {
		return $this->getParent()->project_id;
	}


	public function setProject_id($value) {
		$this->getParent()->project_id = (int) $value;
		return $this;
	}


	public function getPackage() {
		return $this->getParent()->package;
	}


	public function setPackage(Package $value) {
		$this->getParent()->package = $value;
		return $this;
	}


	public function getPackage_id() {
		return $this->getParent()->package_id;
	}


	public function setPackage_id($value) {
		$this->getParent()->package_id = (int) $value;
		return $this;
	}


	public function getType() {
		return $this->getParent()->type;
	}


	public function setType($value) {
		$this->getParent()->type = $value;
		return $this;
	}




}
