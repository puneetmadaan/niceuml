<?php

namespace Model\Entity;


class ElementType extends \Nette\Object {

	protected $name;
	protected $label;
	protected $tableName;

	protected $controlFactory;
	protected $placementControlFactory;


	public function __construct(
		$name,
		$label,
		$tableName,
		IElementControlFactory $controlFactory,
		IPlacementControlFactory $placementControlFactory
	) {
		$this->name = $name;
		$this->label = $label;
		$this->tableName = $tableName;
		$this->controlFactory = $controlFactory;
		$this->placementControlFactory = $placementControlFactory;
	}


	public function getName() {
		return $this->name;
	}


	public function getLabel() {
		return $this->label;
	}


	public function getTableName() {
		return $this->tableName;
	}


	public function getControlFactory() {
		return $this->controlFactory;
	}


	public function getPlacementControlFactory() {
		return $this->placementControlFactory;
	}

}
