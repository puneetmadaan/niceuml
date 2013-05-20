<?php

namespace Model\Entity;


class RelationChild extends BaseChild
{

	protected $parentFields = array('name', 'start', 'start_id', 'end', 'end_id', 'type');
	protected $parentMethods = array('setStart', 'setEnd', 'getOtherEnd');

	public function setParent(BaseEntity $parent) {
		if (!$parent instanceof Relation)
			throw new \Nette\InvalidArgumentException;
		return parent::setParent($parent);
	}

}
