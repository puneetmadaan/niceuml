<?php



class ElementControl extends BaseControl {

	/** @persistent */
	public $relation;

	/** @var Model\BaseDAO */
	protected $elementModel;

	/** @var Model\BaseDAO */
	protected $relationModel;

	/** @var NewRelationControlFactory */
	protected $newRelationControlFactory;

	/** @var RelationControlFactory */
	protected $relationControlFactory;

	/** @var FormFactory */
	protected $formFactory;

	/** @var Model\Entity\Element */
	protected $element;

	/** @var Model\Entity\Relation */
	// protected $relation;


	public function __construct(
		Model\Entity\Element $element, Model\BaseDAO $elementModel, Model\BaseDAO $relationModel,
		NewRelationControlFactory $newRel, RelationControlFactory $editRel, FormFactory $formFactory
	) {
		$this->element = $element;
		$this->elementModel = $elementModel;
		$this->relationModel = $relationModel;
		$this->newRelationControlFactory = $newRel;
		$this->relationControlFactory = $editRel;
		$this->formFactory = $formFactory;
	}


	public function handleDeleteRelation() {
		$relation = $this->checkRelation($this->relation);
		if (!$this->presenter->user->isAllowed($relation, 'delete'))
			$this->presenter->forbidden();
		$relation->delete();
		$this->flashMessage('Relation was deleted.');
		$this->redirect('this', array('relation' => NULL));
	}


	public function render() {
		$this->template->element = $this->element;
		$this->template->relation = (bool) $this->relation;
		$this->template->relations = $this->relationModel->table()
			->where('start_id = ? OR end_id = ?', $this->element->id, $this->element->id);
		parent::render();
	}


	protected function createComponentForm() {
		$form = $this->formFactory->create();

		$form->addText('name', 'Name')
			->setDefaultValue($this->element->name)
			->setRequired("Enter element name")
			->addRule($this->checkUniqueName, 'Name already in use.');

		$form->addSubmit('send', 'Save');
		$form->onSuccess[] = $this->formSucceeded;
		return $form;
	}


	public function checkUniqueName($input) {
		$row = $this->element->project->related('element')->where(array(
			'id != ?' => $this->element->id,
			'name' => $input->value
		))->fetch();
		return $row ? FALSE : TRUE;
	}


	public function formSucceeded($form) {
		$this->elementModel->save($this->element, $form->values);
		$this->presenter->flashMessage('Data saved.');
		$this->redirect('this');
	}


	protected function createComponentNewRelationControl() {
		return $this->newRelationControlFactory->create($this->element);
	}


	protected function createComponentRelationControl() {
		$relation = $this->checkRelation($this->relation);
		return $this->relationControlFactory->create($relation);
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
