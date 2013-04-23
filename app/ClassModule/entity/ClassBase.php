<?php

namespace ClassModule\Entity;


/** Class is a PHP keyword */

class Klass extends \Model\Entity\Base {

	protected $parent;
	protected $child;

	protected $tableNames = array(
		'core_element' => array(NULL, 'class_class'),
		'class_class' => array('core_element', NULL),
	);

	protected $columns = array(
		'core_element' => array(),
		'class_class' => array(),
	);



}
