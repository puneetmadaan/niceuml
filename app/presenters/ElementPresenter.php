<?php


final class ElementPresenter extends ModellingPresenter {

	/** @var Model\ElementDAO */
	protected $elementModel;

	/** @var Model\ElementType */
	protected $elementType;

	/** @var ElementControlFactory */
	protected $elementControlFactory;

	/** @var Model\RelationDAO */
	protected $relationModel;

	/** @var Model\RelationType */
	protected $relationType;

	/** @var RelationControlFactory */
	protected $relationControlFactory;

	/** @var Model\Entity\Element */
	protected $element;

	/** @var Model\Entity\Relation */
	protected $relation;

	protected $newElementType;
	protected $newRelationType;


	public function injectElements(Model\ElementDAO $model, Model\ElementType $type, ElementControlFactory $ctrlFactory) {
		$this->doInject('elementModel', $model);
		$this->doInject('elementType', $type);
		$this->doInject('elementControlFactory', $ctrlFactory);
	}


	public function injectRelations(Model\RelationDAO $model, Model\RelationType $type, RelationControlFactory $ctrlFactory) {
		$this->doInject('relationModel', $model);
		$this->doInject('relationType', $type);
		$this->doInject('relationControlFactory', $ctrlFactory);
	}


	public function renderDefault() {
		$this->template->elements = $this->elementModel->table()->where('project_id', $this->project->id);
	}


	public function actionNew($type = NULL) {
		if ($type !== NULL) {
			if (!$this->elementType->has($type))
				$this->error();
			$this->newElementType = $type;
		}
	}


	public function actionEdit($id, $relation = NULL, $relationType = NULL) {
		$this->element = $this->checkElement($id);
		if ($relation !== NULL)
			$this->relation = $this->checkRelation($relation);
		elseif ($relationType !== NULL) {
			if (!$this->relationType->has($relationType, $this->element))
				$this->error();
			$this->newRelationType = $relationType;
		}
	}


	public function renderEdit() {
		$this->template->element = $this->element;
		$this->template->relation = $this->relation;
		$this->template->relations = $this->relationModel->findByElement($this->element);
	}


	public function handleDelete($element = NULL) {
		if (empty($this->element) === empty($element))
			$this->error();
		$element = $this->element ?: $this->checkElement($element);

		if (!$this->user->isAllowed($element, 'delete'))
			$this->forbidden();
		$element->delete();
		$this->flashMessage('Element was deleted.');
		$this->redirect('default');
	}


	public function handleDeleteRelation() {
		if (empty($this->relation))
			$this->error();

		if (!$this->presenter->user->isAllowed($this->relation, 'delete'))
			$this->presenter->forbidden();
		$this->relation->delete();
		$this->flashMessage('Relation was deleted.');
		$this->redirect('this', array('relation' => NULL));
	}


	protected function createComponentElementControl()
	{
		if ($this->element || $this->newElementType) {
			$type = $this->element ? $this->element->type : $this->newElementType;
			$control = $this->elementControlFactory->create($type);
			if ($this->element)
				$control->setElement($this->element);
			$control->setType($type);
			$control->setProject($this->project);
			return $control;
		}
		$form = $this->formFactory->create();
		$form->addSelect('type', 'Type', $this->elementType->getLabels())
			->setPrompt('Choose type.')
			->setRequired('Choose type.');
		$form->addSubmit('send', 'Create');
		$form->onSuccess[] = $this->elementTypeFormSucceeded;
		return $form;
	}


	public function elementTypeFormSucceeded($form)
	{
		$this->redirect('new', $form['type']->value);
	}


	protected function checkElement($id)
	{
		if ($id === NULL)
			$this->error();
		$element = $this->elementModel->get((int) $id);
		if (!$element || $element->project_id !== $this->project->id)
			$this->error();
		if (!$this->user->isAllowed($element, 'edit'))
			$this->forbidden();
		return $element;
	}


	protected function createComponentRelationControl() {
		if (!$this->element)
			$this->error();

		if ($this->relation || $this->newRelationType) {
			$type = $this->relation ? $this->relation->type : $this->newRelationType;
			$control = $this->relationControlFactory->create($type);
			if ($this->relation)
				$control->setRelation($this->relation);
			$control->setType($type);
			$control->setElement($this->element);
			return $control;
		}
		$form = $this->formFactory->create();
		$form->addSelect('type', 'Type', $this->relationType->getLabels($this->element))
			->setPrompt('Choose type.')
			->setRequired('Choose type.');
		$form->addSubmit('send', 'Create');
		$form->onSuccess[] = $this->relationTypeFormSucceeded;
		return $form;
	}


	public function relationTypeFormSucceeded($form)
	{
		$this->redirect('edit', array(
			'id' => $this->element->id,
			'relationType' => $form['type']->value,
		));
	}


	protected function checkRelation($id)
	{
		if ($id === NULL)
			$this->error();
		$relation = $this->relationModel->get((int) $id);
		if (!$relation) // relation source deletes them
			$this->redirect('this', array('relation' => NULL));
		$el = $this->element ? $this->element->id : NULL;
		if ($relation->start_id !== $el && $relation->end_id !== $el)
			$this->error();
		if (!$this->user->isAllowed($relation, 'edit'))
			$this->forbidden();
		return $relation;
	}
}
