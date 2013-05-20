<?php

namespace Model\Database;


interface IEntityFactory
{


	/**
	 * Creates entity (table row)
	 * @param  array           data of the row
	 * @param  Table\Selection table of the row
	 * @return mixed           created entity
	 */
	public function create(array $data, \Nette\Database\Table\Selection $table);

}
