<?php

namespace NiceDAO;

use Nette\Database\Table,
	Nette\ObjectMixin;


class Entity extends Table\ActiveRow {


	protected function getColumn($name) {
		return parent::__get($name);
	}


	protected function setColumn($name, $value) {
		parent::__set($name, $value);
		return $this;
	}


	protected function issetColumn($name) {
		parent::__isset($name);
		return $this;
	}


	public function & __get($name) {
		if (ObjectMixin::has($this, $name) || method_exists($this, $name))
			return ObjectMixin::get($this, $name);
		$value = $this->getColumn($name);
		return $value;
	}


	public function __set($name, $value) {
		if ($name !== '') {
			$method = 'set'.ucfirst($name);
			if (method_exists($this, $method)) {
				$this->$method($value);
				return;
			}
		}
		$this->setColumn($name, $value);
	}


	public function __isset($name) {
		if (ObjectMixin::has($this, $name)) {
			$value = ObjectMixin::get($this, $name);
			return isset($value);
		}
		return $this->issetColumn($name);
	}


	public function related($key, $throughColumn = NULL) {
		$related = parent::related($key, $throughColumn);
		return $related->select($related->name.'.*');
	}

}
