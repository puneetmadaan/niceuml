<?php

namespace Model;


interface ISourceModel {

	function load(Entity\Project $project, $name, $source);

	function dump(Entity\Project $project);

}
