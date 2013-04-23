<?php


interface IProjectTreeControlFactory {

	/** @return Nette\ComponentModel\IComponent */
	function create(Model\Entity\Project $project);

}
