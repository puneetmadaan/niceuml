<?php


interface IRelationControlFactory {

	/** @return Nette\ComponentModel\IComponent */
	function create(Model\Entity\Relation $relation);

}
