<?php

namespace Model\Entity;


class BaseParent extends Base {

	/** @var BaseChild */
	protected $child;

	/** @var array */
	protected $childTables;

	/** @var string */
	protected $typeColumn;


	public function __construct(array $data, \Nette\Database\Table\Selection $table, array $childTables = array(), $typeColumn = 'type') {
		parent::__construct($data, $table);
		$this->childTables = $childTables;
		$this->typeColumn = (string) $typeColumn;
	}


	public function setChildTables(array $tables) {
		$this->childTables = $tables;
	}


	public function setTypeColumn($typeColumn) {
		$this->typeColumn = (string) $typeColumn;
	}


	public function getChild() {
		if ($this->child === NULL) {
			$type = $this->{$this->typeColumn};
			if (!isset($this->childTables[$type]))
				$child = NULL;
			else
				$child = $this->ref($this->childTables[$type], 'id');
			$this->child = $child !== NULL ? $child : FALSE;
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
