<?php


interface INewDiagramControlFactory {

	/** @return Nette\ComponentModel\IComponent */
	function create(Model\Entity\Project $project);

}
