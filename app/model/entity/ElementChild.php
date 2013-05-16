<?php

namespace Model\Entity;


class ElementChild extends BaseChild {

	protected $parentFields = array('name', 'caption', 'project', 'project_id', 'type');
	protected $parentMethods = array('getCaption', 'setProject');

	public function setParent(BaseEntity $parent) {
		if (!$parent instanceof Element)
			throw new \Nette\InvalidArgumentException;
		return parent::setParent($parent);
	}

}
