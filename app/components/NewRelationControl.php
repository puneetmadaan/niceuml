<?php


class NewRelationControl extends BaseControl {

	protected $element;
	protected $formFactory;

	protected $types = array();
	protected $allowed = array();
	protected $allowedAll = array();
	protected $models = array();


	public function __construct(Model\Entity\Element $element, FormFactory $formFactory) {
		$this->element = $element;
		$this->formFactory = $formFactory;
	}


	public function add($type, $label, Model\BaseDAO $model, $allowed = NULL, $allowedTo = NULL) {
		$allowed = ($allowed === NULL) ? $allowed : array_fill_keys((array) $allowed, TRUE);
		$allowedTo = ($allowedTo === NULL) ? $allowedTo : array_fill_keys((array) $allowedTo, TRUE);

		if ($allowed === NULL || !empty($allowed[$this->element->type])) {
			$this->types[$type] = $label;
			$this->models[$type] = $model;
			$this->allowed[$type] = $allowedTo;
		}
	}


	protected function createComponentForm() {
		$form = $this->formFactory->create();

		$form->addSelect('type', 'Type', $this->types)
			->setPrompt('Choose a type')
			->setRequired('Choose a type');

		$form->addText('name', 'Name');

		$allowed = array();
		foreach ($this->allowed as $types) {
			if ($types === NULL) {
				$allowed = NULL;
				break;
			}
			$allowed += $types;
		}

		$elements = $this->element->project->related('element');
		if ($allowed !== NULL)
			$elements->where('type', array_keys($allowed));

		$form->addSelect('end_id', 'To', $elements->fetchPairs('id', 'caption'))
			->setPrompt('Choose element')
			->setRequired('Choose element');

		$form->addSubmit('send', 'Create');

		$form->onSuccess[] = $this->formSucceeded;
		return $form;
	}


	public function formSucceeded($form) {
		$values = $form->values;

		if ($this->allowed[$values->type] !== NULL) {
			$element = $this->element->project->related('element')->get($values->end_id);
			if (empty($this->allowed[$values->type][$element->type])) {
				$form['end_id']->addError('Element not compatible.');
				return;
			}
		}

		$model = $this->models[$values->type];
		$relation = $model->create($values);
		$relation->start_id = $this->element->id;
		$relation = $model->save($relation);

		$this->presenter->flashMessage('Relation created.');
		$this->redirect('this');
	}


	public function render() {
		$this['form']->render();
	}
}
