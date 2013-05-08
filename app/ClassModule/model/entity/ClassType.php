<?php

namespace ClassModule\Model\Entity;


class ClassType extends \Model\Entity\ElementChild {

	protected $attributes;
	protected $operations;


	public function getAttributes() {
		if ($this->attributes === NULL)
			$this->attributes = $this->related('class_attribute.class_id');
		return $this->attributes;
	}


	public function getOperations() {
		if ($this->operations === NULL)
			$this->operations = $this->related('class_operation.class_id');
		return $this->operations;
	}


	public function setAttributes($attributes) {
		$this->related('class_attribute')->delete();
		foreach ($attributes as $attr)
			$this->related('class_attribute')->insert($attr);
	}


	public function setOperations($operations) {

		$this->related('class_operation')->delete();
		foreach ($operations as $oName => $op) {
			$this->related('class_operation')->insert($op);
		}
	}


}
