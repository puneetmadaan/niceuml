<?php


interface IPlacementControlFactory {

	/** @return Nette\ComponentModel\IComponent */
	function create(Model\Entity\Diagram $diagram, Model\Entity\Element $element);

}
