<?php

namespace Model\Entity;


class ElementChild extends BaseChild {


	public function __construct(array $data, \Nette\Database\Table\Selection $table) {
		parent::__construct($data, $table, 'core_element');
	}


	public function setParent(BaseParent $parent) {
		if (!$parent instanceof Element)
			throw new \Nette\InvalidArgumentException;
		return parent::setParent($parent);
	}


	public function getName() {
		return $this->getParent()->name;
	}


	public function setName($value) {
		$this->getParent()->name = $value;
		return $this;
	}


	public function getCaption() {
		return $this->getParent()->getCaption();
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
