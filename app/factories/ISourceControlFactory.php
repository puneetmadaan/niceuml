<?php


interface ISourceControlFactory {

	/** @return Nette\ComponentModel\IComponent */
	function create(Model\Entity\Project $project);

}
