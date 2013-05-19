<?php


interface IElementControl
{

	public function setElement(Model\Entity\Element $element);

	public function setType($type);

	public function setProject(Model\Entity\Project $project);

	function render();

}
