<?php


interface IDiagramMenuControlFactory {

	/** @return Nette\ComponentModel\IComponent */
	function create(Model\Entity\Diagram $diagram);

}
