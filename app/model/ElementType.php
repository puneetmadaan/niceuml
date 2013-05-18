<?php

namespace Model;

use Nette;


class ElementType extends Nette\Object
{


	protected $types = array();


	public function add($type, $label)
	{
		if ($this->has($type))
			throw new Nette\InvalidArgumentException("Type '$type' already set.");
		$this->types[$type] = $label;
	}


	public function has($type)
	{
		return array_key_exists($type, $this->types);
	}


	public function get()
	{
		return array_keys($this->types);
	}


	public function getLabels()
	{
		return $this->types;
	}

}
