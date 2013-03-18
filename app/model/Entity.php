<?php


namespace Model;

use Nette\Database\Table;
use Nette\ObjectMixin;


class Entity extends Table\ActiveRow {


	public function & __get($name) {
		if (ObjectMixin::has($this, $name) || method_exists($this, $name))
			return ObjectMixin::get($this, $name);
		return parent::__get($name);
	}


	public function __set($name, $value) {
		if ($name !== '') {
			$method = 'set'.ucfirst($name);
			if (method_exists($this, $name)) {
				$this->$method($value);
				return;
			}
		}
		parent::__set($name, $value);
	}


	public function __isset($name) {
		if (ObjectMixin::has($this, $name)) {
			$value = ObjectMixin::get($this, $name);
			return isset($value);
		}
		return  parent::__isset($name);
	}

}