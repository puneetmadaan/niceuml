<?php

namespace Model;


interface ISourceModel
{

	function load(array $source, Entity\Project $project, $original = NULL);

	function dump($item);

}
