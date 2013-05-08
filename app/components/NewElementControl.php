<?php


class NewElementControl extends BaseControl {

	protected $project;
	protected $model;
	protected $formFactory;

	protected $types = array();
	protected $models = array();


	public function __construct(Model\Entity\Project $project, Model\Element $model, IFormFactory $formFactory) {
		$this->project = $project;
		$this->model = $model;
		$this->formFactory = $formFactory;
	}


	public function add($type, $label, Model\IModel $model) {
		$this->types[$type] = $label;
		$this->models[$type] = $model;
	}


	protected function createComponentForm() {
		$form = $this->formFactory->create();

		$form->addSelect('type', 'Type', $this->types)
			->setPrompt('Choose a type')
			->setRequired('Choose a type');

		$form->addText('name', 'Name')
			->addCondition($form::FILLED)
			->addRule($this->checkUniqueName, 'Name already in use.');

		$form->addSubmit('send', 'Create');

		$form->onSuccess[] = $this->formSucceeded;
		return $form;
	}


	public function checkUniqueName($input) {
		$row = $this->project->related('element')->where('name', $input->value)->fetch();
		return $row ? FALSE : TRUE;
	}


	public function formSucceeded($form) {
		$values = $form->values;

		$model = $this->model;
		if (!$values->name) {
			$count = $this->project->related('element')->where('type', $values->type)->count('id');
			do {
				$count++;
				$name = $values->type . ' ' . $count;
				$row = $this->project->related('element')->where('name', $name)->fetch();
			} while ($row);
			$values->name = $name;
		}

		$element = $model->create($values);
		$element->project = $this->project;
		$element = $model->save($element);

		$this->presenter->flashMessage('Element created.');
		$this->presenter->redirect(':Element:edit', $element->id);
	}


	public function render() {
		$this['form']->render();
	}
}
