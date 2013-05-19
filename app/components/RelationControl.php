<?php


class RelationControl extends BaseControl implements IRelationControl
{

	protected $model;
	protected $types;
	protected $elementModel;
	protected $formFactory;

	protected $relation;
	protected $type;
	protected $element;


	public function __construct(Model\BaseDAO $model, Model\RelationType $types, Model\ElementDAO $elementModel, FormFactory $formFactory)
	{
		$this->model = $model;
		$this->types = $types;
		$this->elementModel = $elementModel;
		$this->formFactory = $formFactory;
	}


	public function setRelation(Model\Entity\Relation $relation)
	{
		$this->relation = $relation;
	}


	public function setType($type)
	{
		if (!$this->types->has($type))
			throw new Nette\InvalidArgumentException("Invalid relation type '$type'.");
		$this->type = $type;
	}


	public function setElement(Model\Entity\Element $element)
	{
		$this->element = $element;
	}


	protected function createComponentForm()
	{
		$form = $this->formFactory->create();

		$types = $this->types->getElementTypes($this->type, $this->element->type);
		$elements = $this->elementModel->findByProject($this->element->project, $types);
		$form->addSelect('end_id', 'To', $elements->fetchPairs('id', 'caption'))
			->setPrompt('Choose element')
			->setRequired('Choose element.');

		$form->addText('name', 'Name');

		$this->addFormControls($form);

		$form->addSubmit('send', 'Save');
		$form->onSuccess[] = $this->formSucceeded;
		if ($this->relation)
			$form->setDefaults($this->relation);
		return $form;
	}


	protected function addFormControls($form)
	{
	}


	public function formSucceeded($form) {
		$values = $form->values;
		if (!$this->relation) {
			$values->start = $this->element;
			$values->type = $this->type;
		}
		$this->model->save($this->relation, $values);
		$this->presenter->flashMessage('Data saved.');
		$this->presenter->redirect('edit', $this->element->id);
	}


	public function render() {
		$this['form']->render();
	}

}
