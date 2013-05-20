<?php

namespace Model;

use Nette;


/** Diagram types manager */
class DiagramType extends Nette\Object
{

	/** @var array of type => label */
	protected $types = array();
	/** @var array of type => allowedELements */
	protected $elements = array();


	/**
	 * @param string
	 * @param string
	 * @param array
	 * @return void
	 */
	public function add($type, $label, array $allowedElements = NULL)
	{
		if ($this->has($type))
			throw new Nette\InvalidArgumentException("Type '$type' already set.");
		$this->types[$type] = $label;

		$this->elements[$type] = $allowedElements;
	}


	/**
	 * @param string
	 * @return bool
	 */
	public function has($type)
	{
		return array_key_exists($type, $this->types);
	}


	/** @return array */
	public function get()
	{
		return array_keys($this->types);
	}


	/** @return array|NULL */
	public function getElementTypes($type)
	{
		return array_key_exists($type, $this->elements) ? $this->elements[$type] : array();
	}


	/** @return array */
	public function getLabels()
	{
		return $this->types;
	}

}
