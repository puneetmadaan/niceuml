<?php

namespace Model;

use Nette;


class RelationType extends Nette\Object
{

	const ALL = NULL;

	protected $types = array();    // type => label
	// protected $elements = array(); // type => from => to

	public function add($type, $label, array $allowedElements = NULL)
	{
		if ($this->has($type))
			throw new Nette\InvalidArgumentException("Type '$type' already set.");
		$this->types[$type] = $label;

		// if ($allowedElements === self::ALL)
		// 	$allowedElements = array(self::ALL => self::ALL);

		// if (!isset($allowedElements[self::ALL]))
		// 	$allowedElements[self::ALL] = array();

		// $this->elements[$type] = $allowedElements;
	}


	public function has($type)
	{
		return array_key_exists($type, $this->types);
	}


	public function get()
	{
		return array_keys($this->types);
	}


	public function getElementTypes($type)
	{
		return self::ALL;
	}


	public function getLabels()
	{
		return $this->types;
	}

}
