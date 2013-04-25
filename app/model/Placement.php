<?php

namespace Model;


class Placement extends Base {


	protected function isNew(\NiceDAO\Entity $entity) {
		return $entity->table instanceof \NiceDAO\NewEntityTable;
	}

}
