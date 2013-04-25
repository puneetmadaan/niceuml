<?php

namespace ClassModule;

use Model\Command;


class Parser extends \Nette\Object implements \IParser {

	public function parse($input) {
		$input = \Nette\Utils\Strings::trim($input);
		$words = \Nette\Utils\Strings::split($input, "/\s+/");

		return new Command('class', array_shift($words), $words);
	}


	public function build(Command $command) {
		return $command->name . ' ' . implode(' ', $command->args);
	}

}
