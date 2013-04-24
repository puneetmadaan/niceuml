<?php

namespace Model\Entity;


class BaseParent extends Base {

	/** @var BaseChild */
	protected $child;

	/** @var string */
	protected $childTable;


	public function __construct(array $data, \Nette\Database\Table\Selection $table, $childTable = NULL) {
		parent::__construct($data, $table);
		$this->childTable = $childTable;
	}


	public function setChildTable($table) {
		$this->childTable = $table;
	}


	public function getChild() {
		if ($this->child === NULL) {
			if ($this->childTable === NULL)
				throw new \Nette\InvalidStateException;
			$child = $this->ref($this->childTable, 'id');
			$this->child = $child === NULL ? $child : FALSE;
			if ($this->child)
				$child->setParent($this);
		}
		return $this->child ?: NULL;
	}


	public function setChild(BaseChild $child) {
		if ($this->child === $child)
			return $this;
		$this->child = $child;
		$this->child->setParent($this);
		return $this;
	}

}
