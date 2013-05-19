<?php



class ElementControl extends BaseControl {

	/** @var Model\BaseDAO */
	protected $model;

	/** @var Model\ElementType */
	protected $types;

	/** @var FormFactory */
	protected $formFactory;

	/** @var Model\Entity\Element */
	protected $element;

	protected $type;

	/** @var Model\Entity\Project */
	protected $project;

	public function __construct(Model\BaseDAO $model, Model\ElementType $types, FormFactory $formFactory) {
		$this->model = $model;
		$this->types = $types;
		$this->formFactory = $formFactory;
	}


	public function setElement(Model\Entity\Element $element)
	{
		$this->element = $element;
	}


	public function setType($type)
	{
		if (!$this->types->has($type))
			throw new Nette\InvalidArgumentException("Invalid element type '$type'.");
		$this->type = $type;
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

		$this->addFormControls($form);

		$form->addSubmit('send', 'Save');
		$form->onSuccess[] = $this->formSucceeded;

		if ($this->element)
			$form->setDefaults($this->element);
		return $form;
	}


	protected function addFormControls($form)
	{
	}


	public function checkUniqueName($input) {
		$table = $this->project->related('element')->where('name', $input->value);
		if ($this->element)
			$table->where('id != ?', $this->element->id);
		return $table->fetch() ? FALSE : TRUE;
	}


	public function formSucceeded($form) {
		$values = $form->values;
		if (!$this->element) {
			$values->project = $this->project;
			$values->type = $this->type;
		}
		$element = $this->model->save($this->element, $values);
		$this->presenter->flashMessage('Data saved.');
		$this->presenter->redirect('edit', $element->id);
	}

}
