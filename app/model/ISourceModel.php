<?php

namespace Model;


/** Source handler */
interface ISourceModel
{

	/**
	 * Load one item into $project
	 * @param  array
	 * @param  Project
	 * @param  Entity\BaseEntity|NULL
	 * @return Entity\BaseEntity
	 */
	function load(array $source, Entity\Project $project, $original = NULL);


	/**
	 * Dump one item into source
	 * @param  Entity\BaseEntity
	 * @return array
	 */
	function dump($item);

}
