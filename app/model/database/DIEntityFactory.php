<?php

namespace Model\Database;

use Nette,
	Nette\Database\Table;


class DIEntityFactory extends Nette\Object implements IEntityFactory
{

	protected $services;
	protected $default;
	protected $container;


	public function __construct(array $services, $default, Nette\DI\Container $container)
	{
		$this->services = $services;
		$this->default = $default;
		$this->container = $container;
	}


	public function create(array $data, Table\Selection $table)
	{
		$name = $table->getName();
		$service = Nette\Utils\Arrays::get($this->services, $name, $this->default);
		$method = Nette\DI\Container::getMethodName($service, FALSE);
		return $this->container->$method($data, $table);
	}

}
