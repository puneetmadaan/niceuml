<?php

use Model\Command;


class InterpreterList extends Nette\Object implements IInterpreter {

	protected $interpreters = array();


	/**
	 * @param string Module name.
	 * @param IInterpreter
	 */
	public function addInterpreter($module, IInterpreter $interpreter) {
		if (isset($this->interpreters[$module]))
			throw new Nette\InvalidArgumentException("Interpreter of module '$module' already set.");
		$this->interpreters[$module] = $interpreter;
	}


	public function execute(Command $command) {
		$module = $command->getModule();
		if (isset($this->interpreters[$module]))
			throw new Nette\InvalidArgumentException("Unknown command module '$module'.");
		return $this->interpreters[$module]->execute($command);
	}

}
