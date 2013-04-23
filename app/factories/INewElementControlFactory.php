<?php


interface INewElementControlFactory {

	/** @return Nette\ComponentModel\IComponent */
	function create(Model\Entity\Project $project);

}
