<?php


class NewElementControl extends BaseControl {

	protected $project;
	protected $formFactory;

	protected $types = array();
	protected $models = array();


	public function __construct(Model\Entity\Project $project, IFormFactory $formFactory) {
		$this->project = $project;
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

		$form->addText('name', 'Name');

		$form->addSubmit('send', 'Create');

		$form->onSuccess[] = $this->formSucceeded;
		return $form;
	}


	public function formSucceeded($form) {
		$values = $form->values;

		$model = $this->models[$values->type];
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
