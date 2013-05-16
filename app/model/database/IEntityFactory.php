<?php

namespace Model\Database;


interface IEntityFactory
{


	/**
	 * Creates entity (table row)
	 * @param  array           $data  data of the row
	 * @param  Table\Selection $table table of the row
	 * @return mixed                  created entity
	 */
	public function create(array $data, \Nette\Database\Table\Selection $table);

}
