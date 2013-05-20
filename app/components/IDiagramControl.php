<?php


/** Diaram renderer */
interface IDiagramControl
{

	/** @return void */
	function setDiagram(Model\Entity\Diagram $diagram);

	/** @return void */
	function render();

	/** @return void */
	function renderScripts();

}
