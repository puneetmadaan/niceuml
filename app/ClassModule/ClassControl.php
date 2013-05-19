<?php

namespace ClassModule;

use ElementControl,
	FormFactory,
	Model\ElementType,
	Model\Entity\Element,
	ClassModule\Model\ClassType;


class ClassControl extends ElementControl {

	public function __construct(ClassType $model, ElementType $types, FormFactory $formFactory)
	{
		parent::__construct($model, $types, $formFactory);
	}


	public function setElement(Element $element)
	{
		$this->element = $this->model->getByParent($element);
	}


	protected function addFormControls($form) {
		$form->addCheckbox('abstract', 'Abstract');
		$form->addCheckbox('static', 'Static');
		if ($this->element)
			$form->setDefaults($this->element->parent);
	}

}
