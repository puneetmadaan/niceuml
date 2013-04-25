<?php

namespace Model\Entity;


class RelationChild extends BaseChild {


	public function __construct(array $data, \Nette\Database\Table\Selection $table) {
		parent::__construct($data, $table, 'core_relation');
	}


	public function setParent(BaseParent $parent) {
		if (!$parent instanceof Relation)
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


	public function getStart() {
		return $this->getParent()->start;
	}


	public function setStart(Element $value) {
		$this->getParent()->start = $value;
		return $this;
	}


	public function getStart_id() {
		return $this->getParent()->start_id;
	}


	public function setStart_id($value) {
		$this->getParent()->start_id = (int) $value;
		return $this;
	}


	public function getEnd() {
		return $this->getParent()->end;
	}


	public function setEnd(Element $value) {
		$this->getParent()->end = $value;
		return $this;
	}


	public function getEnd_id() {
		return $this->getParent()->end_id;
	}


	public function setEnd_id($value) {
		$this->getParent()->end_id = (int) $value;
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
