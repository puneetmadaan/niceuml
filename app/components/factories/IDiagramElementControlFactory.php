<?php


interface IDiagramElementControlFactory {

	/** @return Nette\ComponentModel\IComponent */
	function create(Model\Entity\Diagram $diagram, Model\Entity\Element $element);

}
