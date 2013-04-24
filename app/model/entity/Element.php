<?php

namespace Model\Entity;


class Element extends Base {

	/** @var ElementChild */
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


	public function setChild(ElementChild $child) {
		if ($this->child === $child)
			return $this;
		if ($this->child !== NULL)
			throw new \Nette\InvalidStateException;
		$this->child = $child;
		$this->child->setParent($this);
		return $this;
	}


	/** @return Element self */
	public function setProject(Project $project) {
		$this->setColumn('project_id', (int) $project->id);
		return $this;
	}


	/** @return Element self */
	public function setPackage(Package $package) {
		$this->setColumn('package_id', (int) $package->id);
		return $this;
	}

}
