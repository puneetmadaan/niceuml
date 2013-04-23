<?php


interface IElementControlFactory {

	/** @return Nette\ComponentModel\IComponent */
	function create(Model\Entity\Element $element);

}
