<?php


interface IProjectAccessControlFactory {

	/** @return Nette\ComponentModel\IComponent */
	function create(Model\Entity\Project $project);

}
