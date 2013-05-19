<?php


final class ElementPresenter extends ModellingPresenter {

	/** @var Model\ElementDAO */
	protected $elementModel;

	/** @var Model\ElementType */
	protected $elementType;

	/** @var ElementControlFactory */
	protected $elementControlFactory;

	/** @var Model\Entity\Element */
	protected $element;

	protected $type;


	/** @var Model\RelationDAO */
	protected $relationModel;

	/** @var NewRelationControlFactory */
	protected $newRelationControlFactory;

	/** @var RelationControlFactory */
	protected $relationControlFactory;

	/** @var Model\Entity\Relation */
	protected $relation;


	public function injectElements(Model\ElementDAO $model, Model\ElementType $type, ElementControlFactory $controlFactory) {
		$this->doInject('elementModel', $model);
		$this->doInject('elementType', $type);
		$this->doInject('elementControlFactory', $controlFactory);
	}


	public function injectRelations(Model\RelationDAO $model, NewRelationControlFactory $new, RelationControlFactory $edit) {
		$this->doInject('relationModel', $model);
		$this->doInject('newRelationControlFactory', $new);
		$this->doInject('relationControlFactory', $edit);
	}


	public function actionDefault() {
	}


	public function renderDefault() {
		$this->template->elements = $this->elementModel->table()->where('project_id', $this->project->id);
	}


	public function actionNew($type = NULL) {
		if ($type !== NULL) {
			if (!$this->elementType->has($type))
				$this->error();
			$this->type = $type;
		}
	}


	public function actionEdit($id, $relation = NULL) {
		$this->element = $this->checkElement($id);
		if ($relation !== NULL)
			$this->relation = $this->checkRelation($relation);
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
		if ($this->element || $this->type) {
			$type = $this->element ? $this->element->type : $this->type;
			$control = $this->elementControlFactory->create($type);
			if ($this->element)
				$control->setElement($this->element);
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


	protected function createComponentNewRelationControl() {
		return $this->newRelationControlFactory->create($this->element);
	}


	protected function createComponentRelationControl() {
		if (!$this->relation)
			$this->error();
		return $this->relationControlFactory->create($this->relation);
	}


	protected function checkRelation($id)
	{
		if ($id === NULL)
			$this->error();
		$relation = $this->relationModel->get((int) $id);
		$el = $this->element ? $this->element->id : NULL;
		if (!$relation || ($relation->start_id !== $el && $relation->end_id !== $el))
			$this->error();
		if (!$this->user->isAllowed($relation, 'edit'))
			$this->forbidden();
		return $relation;
	}
}
