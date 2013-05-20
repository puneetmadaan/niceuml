<?php

namespace ClassModule;

use ElementControl,
	FormFactory,
	Model\ElementType,
	Model\Entity\Element,
	ClassModule\Model\ClassDAO;


/**
 * Class form
 */
class ClassControl extends ElementControl
{


	public function __construct(ClassDAO $model, ElementType $types, FormFactory $formFactory)
	{
		parent::__construct($model, $types, $formFactory);
	}


	/** @return void */
	public function setElement(Element $element)
	{
		$this->element = $this->model->getByParent($element);
	}


	/** @return void */
	protected function addFormControls($form)
	{
		$form->addCheckbox('abstract', 'Abstract');
		$form->addCheckbox('static', 'Static');
		if ($this->element)
			$form->setDefaults($this->element->parent);
	}

}
