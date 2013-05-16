<?php

namespace Model\Database;

use Nette,
	Nette\Database\Table;


class ConfigEntityFactory extends Nette\Object implements IEntityFactory
{

	protected $classes;
	protected $defaultClass;


	public function __construct(array $classes, $defaultClass)
	{
		$this->classes = $classes;
		$this->defaultClass = $defaultClass;
	}


	public function create(array $data, Table\Selection $table)
	{
		$name = $table->getName();
		$class = isset($this->classes[$name]) ? $this->classes[$name] : $this->defaultClass;
		$entity = new $class($data, $table);
		return $entity;
	}

}
