<?php


interface IDiagramControl
{

	function setDiagram(Model\Entity\Diagram $diagram);

	function render();

	function renderScripts();

}
