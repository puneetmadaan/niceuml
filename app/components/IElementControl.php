<?php


/** Element form */
interface IElementControl
{

	/** @return void */
	public function setElement(Model\Entity\Element $element);

	/** @return void */
	public function setType($type);

	/** @return void */
	public function setProject(Model\Entity\Project $project);

	/** @return void */
	function render();

}
