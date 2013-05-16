<?php

namespace Model\Database;

use Nette,
	Nette\Database\Table;


class GroupedSelection extends Table\GroupedSelection
{


	/**
	* Creates filtered and grouped table representation.
	* @param Selection|GroupedSelection $refTable
	* @param string database table name
	* @param string joining column
	*/
	public function __construct(Table\Selection $refTable, $table, $column)
	{
		if (!($refTable instanceof Selection || $refTable instanceof GroupedSelection))
			throw new Nette\InvalidArgumentException;
		parent::__construct($refTable, $table, $column);
	}


	public function collect($item, $preserveKeys = FALSE)
	{
		return SelectionMixin::collect($this, $item, $preserveKeys);
	}


	protected function createRow(array $row)
	{
		return $this->getRefTable($path)->entityFactory->create($row, $this);
	}


	protected function createSelectionInstance($table = NULL)
	{
		return new Selection($table ?: $this->name, $this->connection, $this->getRefTable($path)->entityFactory);
	}


	protected function createGroupedSelectionInstance($table, $column)
	{
		return new GroupedSelection($this, $table, $column);
	}

}
