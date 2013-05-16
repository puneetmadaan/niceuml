<?php

namespace Model\Entity;


class RelationChild extends BaseChild {

	protected $parentFields = array('name', 'start', 'start_id', 'end', 'end_id', 'type');

	public function setParent(Base $parent) {
		if (!$parent instanceof Relation)
			throw new \Nette\InvalidArgumentException;
		return parent::setParent($parent);
	}

}
