<?php

namespace Model\Entity;


class ElementChild extends BaseChild {

	protected $parentFields = array('name', 'caption', 'project', 'project_id', 'type');
	protected $parentMethods = array('getCaption');

}
