<?php


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


interface IElementControlAccessor {
	/** @param type */
	/** @return ElementControl */
	public function get($type);
}


interface IDiagramControlAccessor {
	/** @param type */
	/** @return DiagramControl */
	public function get($type);
}

interface IDiagramMenuControlAccessor {
	/** @param type */
	/** @return DiagramMenuControl */
	public function get($type);
}

interface IDiagramElementControlAccessor {
	/** @param type */
	/** @return DiagramElementControl */
	public function get($type);
}

class ParsingException extends Nette\InvalidStateException {
}


interface IUserControl extends Nette\ComponentModel\IComponent {
}
