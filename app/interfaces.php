<?php


interface IRouterFactory {

	/** @return Nette\Application\IRouter */
	public function createRouter();

}


interface IParser {

	/** @param string */
	/** @return Command|NULL */
	public function parse($input);


	/** @param Command */
	/** @return string */
	public function build(Command $command);

}

interface IInterpreter {

	/** @param Command */
	/** @return mixed */
	public function execute(Command $command);

}


class ParsingException extends Nette\InvalidStateException {
}
