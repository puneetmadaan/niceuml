<?php

namespace Model\Entity;


class BaseChild extends Base {

	/** @var BaseParent */
	protected $parent;

	/** @var string */
	protected $parentTable;


	public function __construct(array $data, \Nette\Database\Table\Selection $table, $parentTable = NULL) {
		parent::__construct($data, $table);
		$this->parentTable = $parentTable;
	}


	public function setParentTable($table) {
		$this->parentTable = $table;
	}


	public function getParent() {
		if ($this->parent === NULL) {
			$parent = $this->ref($this->parentTable, 'id');
			$this->parent = $parent !== NULL ? $parent : FALSE;
			if ($this->parent)
				$parent->setChild($this);
		}
		return $this->parent ?: NULL;
	}


	public function setParent(BaseParent $parent) {
		if ($this->parent === $parent)
			return $this;
		$this->parent = $parent;
		$this->parent->setChild($this);
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

}

