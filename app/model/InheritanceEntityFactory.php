<?php

namespace Model;

use NiceDAO\DIEntityFactory,
	Nette\Database\Table,
	Nette\DI\Container;


class InheritanceEntityFactory extends DIEntityFactory {

	protected $inheritanceColumns;


	/**
	 * @param array  $inheritanceColumns  table name => column name
	 */
	public function __construct($prefix, $default, Container $container, array $inheritanceColumns) {
		parent::__construct($prefix, $default, $container);
		$this->inheritanceColumns = $inheritanceColumns;
	}


	public function create(array $data, Table\Selection $table) {
		$name = $table->getName();
		if (isset($this->inheritanceColumns[$name])) {
			$column = $this->inheritanceColumns[$name];

			$table->accessColumn($column);
			if (isset($data[$column])) {
				$subtype = $data[$column];
				$method = Container::getMethodName($this->prefix . $name . '.' . $subtype, FALSE);
				if (method_exists($this->container, $method)) {
					return $this->container->$method($data, $table);
				}
			}
			else {
				if ($table->dataRefreshed) {
					$signature = parent::create($data, $table)->getSignature();
					if (isset($table[$signature]))
						return $table[$signature];
				}
				else
					$table->removeAccessColumn($column);
			}
		}
		return parent::create($data, $table);
	}


}
