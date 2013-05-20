<?php


/** Relation form */
interface IRelationControl
{

	/** @return void */
	public function setRelation(Model\Entity\Relation $relation);

	/** @return void */
	public function setType($type);

	/** @return void */
	public function setElement(Model\Entity\Element $element);

	/** @return void */
	function render();

}
