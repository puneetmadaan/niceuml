<?php

namespace ClassModule\Entity;



class ClassElement extends \Model\Entity\Base {

	public function getParent() {
		return NULL;
	}


	public function getChild() {
		return $this->ref('class_class', 'id');
	}


	protected function whichRowHas($name) {
		self::SELF;
	}


}
