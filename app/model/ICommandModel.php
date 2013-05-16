<?php

namespace Model;

interface ICommandModel
{

	/**
	 * Parses and executes command from console.
	 * @param  string $command command to execute
	 * @return bool            was the command accepted?
	 */
	function execute($command);

}
