<?php

namespace Model;


/** Placement data access object */
class PlacementDAO extends BaseDAO
{


	/** @return bool */
	protected function isNew(Entity\BaseEntity $entity)
	{
		return $entity->table instanceof Database\NewEntityTable;
	}

}
