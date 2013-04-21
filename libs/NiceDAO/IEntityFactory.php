<?php

namespace NiceDAO;

use Nette\Database\Table;


interface IEntityFactory {

	/**
	 * Creates entity (table row)
	 * @param  array           $data  data of the row
	 * @param  Table\Selection $table table of the row
	 * @return Entity                 created entity
	 */
	public function create(array $data, Table\Selection $table);

}
