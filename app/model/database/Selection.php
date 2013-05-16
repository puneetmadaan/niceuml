<?php

namespace Model\Database;

use Nette,
	Nette\Database\Connection,
	Nette\Database\Table;


class Selection extends Table\Selection
{

	protected $entityFactory;


	/**
	 * Creates filtered table representation.
	 * @param  string  database table name
	 * @param  Connection
	 * @param  EntityFactory
	 */
	public function __construct($table, Connection $connection, IEntityFactory $entityFactory)
	{
		parent::__construct($table, $connection);
		$this->entityFactory = $entityFactory;
	}


	public function getEntityFactory()
	{
		return $this->entityFactory;
	}


	public function collect($item, $preserveKeys = FALSE)
	{
		return SelectionMixin::collect($this, $item, $preserveKeys);
	}


	protected function createRow(array $row)
	{
		return $this->entityFactory->create($row, $this);
	}


	protected function createSelectionInstance($table = NULL)
	{
		return new Selection($table ?: $this->name, $this->connection, $this->entityFactory);
	}


	protected function createGroupedSelectionInstance($table, $column)
	{
		return new GroupedSelection($this, $table, $column);
	}

}
