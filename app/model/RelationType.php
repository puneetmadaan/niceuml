<?php

namespace Model;

use Nette;


class RelationType extends Nette\Object
{

	const ALL = NULL;

	protected $types = array();    // type => label
	protected $elements = array(); // type => from => to


	public function add($type, $label, array $allowedElements = NULL)
	{
		if ($this->has($type))
			throw new Nette\InvalidArgumentException("Type '$type' already set.");
		$this->types[$type] = $label;

		if ($allowedElements === NULL)
		 	$allowedElements = array(self::ALL => self::ALL);

		$this->elements[$type] = $allowedElements;
	}


	public function has($type, $elementType = self::ALL)
	{
		if (!array_key_exists($type, $this->types))
			return FALSE;
		return $this->isCompatible($type, $elementType);
	}


	public function get($elementType = self::ALL)
	{
		if ($elementType === self::ALL)
			return array_keys($this->types);

		$types = array();
		foreach ($this->types as $type => $label)
			if ($this->isCompatible($type, $elementType))
				$types[] = $type;
		return $types;
	}


	public function getLabels($elementType = self::ALL)
	{
		if ($elementType === self::ALL)
			return $this->types;

		$types = array();
		foreach ($this->types as $type => $label) {
			if ($this->isCompatible($type, $elementType))
				$types[$type] = $label;
		}
		return $types;
	}


	public function getElementTypes($type, $startType)
	{
		if (!$this->has($type, $startType))
			return array();
		$types = array();
		if (array_key_exists(self::ALL, $this->elements[$type])) {
			if ($this->elements[$type][self::ALL] === self::ALL)
				return self::ALL;
			foreach ($this->elements[$type][self::ALL] as $t)
				$types[$t] = TRUE;
		}
		if (array_key_exists($startType, $this->elements[$type])) {
			if ($this->elements[$type][$startType] === self::ALL)
				return self::ALL;
			foreach ($this->elements[$type][$startType] as $t)
				$types[$t] = TRUE;
		}
		return array_keys($types);
	}


	private function isCompatible($type, $elType)
	{
		if ($elType === self::ALL)
			return TRUE;
		return array_key_exists($elType, $this->elements[$type])
			 || array_key_exists(self::ALL, $this->elements[$type]);
	}

}
