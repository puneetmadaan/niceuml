<?php

namespace Model;


/**
 * Command interpreter
 */
interface ICommandModel
{

	/**
	 * Parses and executes command from console.
	 * @param  string $command         command to execute
	 * @param  Entity\Project $project project to execute on
	 * @return bool                    was the command accepted?
	 */
	function execute($command, Entity\Project $project);

}
