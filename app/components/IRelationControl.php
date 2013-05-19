<?php


interface IRelationControl
{

	public function setRelation(Model\Entity\Relation $relation);

	public function setType($type);

	public function setElement(Model\Entity\Element $element);

	function render();

}
