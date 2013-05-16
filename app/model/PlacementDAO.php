<?php

namespace Model;


class PlacementDAO extends BaseDAO {


	protected function isNew(Entity\BaseEntity $entity) {
		return $entity->table instanceof Database\NewEntityTable;
	}

}
