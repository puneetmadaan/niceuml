<?php

namespace Model\Entity;

use Nette\Database\Table;


abstract class InheritanceBase extends Base {

	const SELF = NULL;
	const PARENT = 0;
	const CHILD = 1;


	abstract public function getParent();

	abstract public function getChild();

	abstract protected function whichRowHasColumn($name);
	abstract protected function whichRowHasReference($name);
	abstract protected function whichRowHasRelated($name);


	protected function getColumn($name) {
		$row = $this->whichRowHasColumn($name);
		return ($row === NULL || $row === $this) ? parent::getColumn($name) : $row->getColumn($name);
	}


	protected function setColumn($name, $value) {
		$row = $this->whichRowHasColumn($name);
		return ($row === NULL || $row === $this) ? parent::setColumn($name) : $row->getColumn($name);
	}

	
	protected function issetColumn($name) {
		$row = $this->whichRowHasColumn($name);
		return ($row === NULL || $row === $this) ? parent::issetColumn($name) : $row->getColumn($name);
	}

}
