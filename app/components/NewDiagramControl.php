<?php


class NewDiagramControl extends BaseControl {

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

		$form->addText('name', 'Name')
			->setRequired('Enter diagram name.');

		$form->addSubmit('send', 'Create');

		$form->onSuccess[] = $this->formSucceeded;
		return $form;
	}


	public function formSucceeded($form) {
		$values = $form->values;

		$model = $this->models[$values->type];
		$diagram = $model->create($values);
		$diagram->project_id = $this->project->id;
		$diagram = $model->save($diagram);

		$this->presenter->flashMessage('Diagram created.');
		$this->presenter->redirect(':Diagram:edit', $diagram->id);
	}


	public function render() {
		$this['form']->render();
	}
}
