<?php



class RelationControl extends BaseControl {

	protected $relation;
	protected $model;
	protected $formFactory;


	public function __construct(Model\Entity\Relation $relation, Model\Relation $model, IFormFactory $formFactory) {
		$this->relation = $relation;
		$this->model = $model;
		$this->formFactory = $formFactory;
	}


	protected function createComponentForm() {
		$form = $this->formFactory->create();

		$form->addText('name', 'Name')
			->setDefaultValue($this->relation->name);
		$form->addSubmit('send', 'Save');
		$form->onSuccess[] = $this->formSucceeded;
		return $form;
	}


	public function formSucceeded($form) {
		$this->model->save($this->relation, $form->values);
		$this->presenter->flashMessage('Data saved.');
		$this->redirect('this');
	}


	public function render() {
		$this['form']->render();
	}
}
