<?php

namespace NiceDAO;

use Nette\Database\Table;


class DIEntityFactory extends \Nette\Object implements IEntityFactory {

	protected $prefix;
	protected $default;
	protected $container;


	public function __construct($prefix, $default, \Nette\DI\Container $container) {
		$this->prefix = $prefix;
		$this->default = $default;
		$this->container = $container;
	}


	public function create(array $data, Table\Selection $table) {
		$name = $table->getName();

		$method = \Nette\DI\Container::getMethodName($this->prefix . $name, FALSE);
		if (!method_exists($this->container, $method))
			$method = \Nette\DI\Container::getMethodName($this->prefix . $this->default, FALSE);

		return $this->container->$method($data, $table);
	}

}
