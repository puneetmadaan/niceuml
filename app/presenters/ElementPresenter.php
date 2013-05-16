<?php


final class ElementPresenter extends ModellingPresenter {

	/** @var Model\Element */
	protected $elementModel;

	/** @var INewElementControlFactory */
	protected $newElementControlFactory;

	/** @var IElementControlFactory */
	protected $elementControlFactory;

	/** @var Model\Entity\Element */
	protected $element;


	public function injectModel(Model\Element $elementModel) {
		$this->doInject('elementModel', $elementModel);
	}


	public function injectElementControlFactories(INewElementControlFactory $new, IElementControlFactory $edit) {
		$this->doInject('newElementControlFactory', $new);
		$this->doInject('elementControlFactory', $edit);
	}

	public function actionDefault() {
	}


	public function renderDefault() {
		$this->template->elements = $this->elementModel->table()->where('project_id', $this->project->id);
	}


	public function actionEdit($id, $relation = NULL) {
		$this->element = $this->checkElement($id);
		if ($relation !== NULL)
			$this->relation = $this->checkRelation($relation);
	}


	public function renderEdit() {
		$this->template->element = $this->element;
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


	protected function createComponentNewElementControl() {
		return $this->newElementControlFactory->create($this->project);
	}


	protected function createComponentElementControl() {
		if (!$this->element)
			$this->error();
		return $this->elementControlFactory->create($this->element);
	}


	protected function checkElement($id) {
		if ($id === NULL)
			$this->error();
		$element = $this->elementModel->get((int) $id);
		if (!$element || $element->project_id !== $this->project->id)
			$this->error();
		if (!$this->user->isAllowed($element, 'edit'))
			$this->forbidden();
		return $element;
	}

}
