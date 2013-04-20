<?php

namespace Model;

use Nette\Database\Table;
use Nette\Utils\Arrays;


class InheritanceEntityFactory extends \NiceDAO\EntityFactory {

	protected $inheritanceColumns;
	protected $inheritanceClasses;

	/**
	 * @param array  $classes             table name => class name
	 * @param string $defaultClass        default class name
	 * @param array  $inheritanceColumns  table name => column name
	 * @param array  $inheritanceClasses   table name => subtype => class name
	 */
	public function __construct(array $classes, $defaultClass, array $inheritanceColumns, array $inheritanceClasses) {
		parent::__construct($classes, $defaultClass);
		$this->inheritanceColumns = $inheritanceColumns;
		$this->inheritanceClasses = $inheritanceClasses;
	}


	public function create(Table\Selection $table, array $data = array()) {
		$name = $table->getName();
		if (isset($this->inheritanceColumns[$name])) {
			$column = $this->inheritanceColumns[$name];

			$table->accessColumn($column);
			if (isset($data[$column])) {
				$subtype = $data[$column];
				if (isset($this->inheritanceClasses[$name][$subtype])) {
					$class = $this->inheritanceClasses[$name][$subtype];
					return new $class($data, $table);
				}
			}
			else {
				if ($table->dataRefreshed) {
					$signature = parent::create($table, $data)->getSignature();
					if (isset($table[$signature]))
						return $table[$signature];
				}
				else
					$table->removeAccessColumn($column);
			}
		}
		return parent::create($table, $data);
	}


}
