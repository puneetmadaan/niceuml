<?php


interface IInterpreter {

	/** @param Model\Command */
	/** @param Project to execute the command upon */
	/** @return mixed */
	public function execute(Model\Command $command, $project = NULL);

}
