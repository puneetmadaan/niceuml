<?php


class NoteControl extends ElementControl {


	public function __construct(Model\BaseChildDAO $model, Model\ElementType $types, FormFactory $formFactory)
	{
		parent::__construct($model, $types, $formFactory);
	}


	public function setElement(Model\Entity\Element $element)
	{
		$this->element = $this->model->getByParent($element);
	}


	protected function addFormControls($form) {
		$form->addTextarea('text', 'Text');
		if ($this->element)
			$form->setDefaults($this->element->parent);
	}


	public function formSucceeded($form) {
		$form['text']->value = trim($form['text']->value);
		parent::formSucceeded($form);
	}

}
