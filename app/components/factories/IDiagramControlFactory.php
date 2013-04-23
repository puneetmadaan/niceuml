<?php


interface IDiagramControlFactory {

	/** @return Nette\ComponentModel\IComponent */
	function create(Model\Entity\Diagram $diagram);

}
