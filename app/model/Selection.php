<?php


namespace Model;

use Nette\Database\Table;


class Selection extends Table\Selection {

	protected $entityFactory;


	public function getEntityFactory() {
		return $entityFactory;
	}


	public function injectEntityFactory(EntityFactory $entityFactory) {
		if ($this->entityFactory !== NULL)
			throw new \Nette\InvalidStateException("Entity factory has already been set.");
		$this->entityFactory = $entityFactory;
		return $this;
	}


	public function collect($item, $preserveKeys = FALSE) {
		return SelectionMixin::collect($this, $item, $preserveKeys);
	}


	protected function createRow(array $row) {
		return $this->entityFactory->create($row, $this);
	}


	protected function createSelectionInstance($table = NULL) {
		$selection = new Selection($table ?: $this->name, $this->connection);
		return $selection->injectEntityFactory($this->entityFactory);
	}


	protected function createGroupedSelectionInstance($table, $column) {
		return new GroupedSelection($this, $table, $column);
	}

}