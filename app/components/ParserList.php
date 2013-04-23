<?php

use Model\Command;


class ParserList extends Nette\Object implements IParser {

	protected $parsers = array();


	/**
	 * @param string Module name.
	 * @param IParser
	 */
	public function addParser($module, IParser $parser) {
		if (isset($this->parsers[$module]))
			throw new Nette\InvalidArgumentException("Parser of module '$module' already set.");
		$this->parsers[$module] = $parser;
	}


	/**
	 * @param string
	 * @return Command
	 * @throws ParsingException
	 */
	public function parse($input) {
		foreach ($this->parsers as $parser) {
			$command = $parser->parse($input);
			if ($command === NULL)
				continue;
			if (!$command instanceof Command)
				throw new Nette\UnexpectedValueException("Parser of module '$module' did not return Command nor NULL.");
			return $command;
		}
		throw new ParsingException('Could not parse input.');
	}


	public function build(Command $command) {
		$module = $command->getModule();
		if (isset($this->parsers[$module]))
			throw new Nette\InvalidArgumentException("Unknown command module '$module'.");
		return $this->parsers[$module]->build($command);
	}

}
