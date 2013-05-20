<?php


/** Basic element form */
class ElementControl extends BaseControl implements IElementControl
{

	/** @var Model\BaseDAO */
	protected $model;

	/** @var Model\ElementType */
	protected $types;

	/** @var FormFactory */
	protected $formFactory;

	/** @var Model\Entity\Element */
	protected $element;

	/** @var string element type */
	protected $type;

	/** @var Model\Entity\Project */
	protected $project;


	public function __construct(Model\BaseDAO $model, Model\ElementType $types, FormFactory $formFactory)
	{
		$this->model = $model;
		$this->types = $types;
		$this->formFactory = $formFactory;
	}


	/** @return void */
	public function setElement(Model\Entity\Element $element)
	{
		$this->element = $element;
	}


	/** @return void */
	public function setType($type)
	{
		if (!$this->types->has($type))
			throw new Nette\InvalidArgumentException("Invalid element type '$type'.");
		$this->type = $type;
	}


	/** @return void */
	public function setProject(Model\Entity\Project $project)
	{
		$this->project = $project;
	}


	/** @return void */
	public function render()
	{
		$this['form']->render();
	}


	/** @return Nette\Application\UI\Form */
	protected function createComponentForm()
	{
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


	/** @return void */
	protected function addFormControls($form)
	{
	}


	/** @return bool */
	public function checkUniqueName($input)
	{
		$table = $this->project->related('element')->where('name', $input->value);
		if ($this->element)
			$table->where('id != ?', $this->element->id);
		return $table->fetch() ? FALSE : TRUE;
	}


	/** @return void */
	public function formSucceeded($form)
	{
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
