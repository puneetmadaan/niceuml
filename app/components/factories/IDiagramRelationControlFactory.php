<?php


interface IDiagramRelationControlFactory {

	/** @return Nette\ComponentModel\IComponent */
	function create(Model\Entity\Diagram $diagram, Model\Entity\Relation $relation);

}
