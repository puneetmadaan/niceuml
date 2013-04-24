<?php

namespace Model;

use Nette;


class Command extends Nette\Object {

	protected $module;
	protected $name;
	protected $args;


	/**
	 * @param string Module name
	 * @param string Command name
	 * @param array Command arguments
	 */
	public function __construct($module, $name, array $args = array()) {
		$this->module = $module;
		$this->name = $name;
		$this->args = $args;
	}


	public function getModule() {
		return $this->module;
	}


	public function getName() {
		return $this->name;
	}


	public function getArgs() {
		return $this->args;
	}

}
