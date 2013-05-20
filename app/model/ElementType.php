<?php

namespace Model;

use Nette;

/** Element types manager */
class ElementType extends Nette\Object
{

	/** @var array of type => label */
	protected $types = array();


	/**
	 * @param string
	 * @param string
	 */
	public function add($type, $label)
	{
		if ($this->has($type))
			throw new Nette\InvalidArgumentException("Type '$type' already set.");
		$this->types[$type] = $label;
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


	/** @return array */
	public function getLabels()
	{
		return $this->types;
	}

}
