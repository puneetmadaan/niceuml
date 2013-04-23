<?php


interface IInterpreter {

	/** @param Model\Command */
	/** @return mixed */
	public function execute(Model\Command $command);

}
