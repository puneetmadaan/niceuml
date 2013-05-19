<?php



class ElementControl extends BaseControl {

	/** @persistent */
	public $relation;

	/** @var Model\BaseDAO */
	protected $elementModel;

	/** @var FormFactory */
	protected $formFactory;

	/** @var Model\Entity\Element */
	protected $element;

	/** @var Model\Entity\Project */
	protected $project;


	public function __construct(Model\BaseDAO $elementModel, FormFactory $formFactory) {
		$this->elementModel = $elementModel;
		$this->formFactory = $formFactory;
	}


	public function setElement(Model\Entity\Element $element)
	{
		$this->element = $element;
	}


	public function setProject(Model\Entity\Project $project)
	{
		$this->project = $project;
	}


	public function render() {
		$this['form']->render();
	}


	protected function createComponentForm() {
		$form = $this->formFactory->create();

		$form->addText('name', 'Name')
			->setRequired("Enter element name")
			->addRule($this->checkUniqueName, 'Name already in use.');

		$form->addSubmit('send', 'Save');
		$form->onSuccess[] = $this->formSucceeded;

		if ($this->element)
			$form->setDefaults($this->element);
		return $form;
	}


	public function checkUniqueName($input) {
		// $row = $this->elementModel->getByProject($this->project)->where(array(
		$table = $this->project->related('element')->where('name', $input->value);
		if ($this->element)
			$table->where('id != ?', $this->element->id);
		return $table->fetch() ? FALSE : TRUE;
	}


	public function formSucceeded($form) {
		$values = $form->values;
		if (!$this->element && $this->project)
			$form->values->project = $this->project;
		$this->elementModel->save($this->element, $form->values);
		$this->presenter->flashMessage('Data saved.');
		$this->redirect('this');
	}


	protected function checkRelation($id) {
		if ($id === NULL)
			$this->presenter->error();
		$relation = $this->relationModel->get((int) $id);
		if (!$relation || ($relation->start_id !== $this->element->id && $relation->end_id !== $this->element->id) )
			$this->presenter->error();
		if (!$this->presenter->user->isAllowed($relation, 'edit'))
			$this->presenter->forbidden();
		return $relation;
	}


}
