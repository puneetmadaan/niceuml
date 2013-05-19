<?php

namespace Model;

use Nette;


class DiagramType extends Nette\Object
{

	protected $types = array();    // type => label
	protected $elements = array(); // type => allowedElements

	public function add($type, $label, array $allowedElements = NULL)
	{
		if ($this->has($type))
			throw new Nette\InvalidArgumentException("Type '$type' already set.");
		$this->types[$type] = $label;

		$this->elements[$type] = $allowedElements;
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
		return array_key_exists($type, $this->elements) ? $this->elements[$type] : array();
	}


	public function getLabels()
	{
		return $this->types;
	}

}
