<?php


final class ElementPresenter extends ModellingPresenter {

	/** @var Model\Element */
	protected $elementModel;

	/** @var INewElementControlFactory */
	protected $newElementControlFactory;

	/** @var IElementControlFactory */
	protected $elementControlFactory;

	/** @var Model\EntityElement */
	protected $element;


	public function injectElementModel(Model\Element $elementModel) {
		$this->doInject('elementModel', $elementModel);
	}


	public function injectElementControlFactories(INewElementControlFactory $new, IElementControlFactory $edit) {
		$this->doInject('newElementControlFactory', $new);
		$this->doInject('elementControlFactory', $edit);
	}


	public function actionDefault() {
	}


	public function renderDefault() {
		$this->template->elements = $this->project->related('element');
	}


	public function actionEdit($id) {
		$this->element = $this->checkElement($id);
	}


	public function renderEdit() {
		$this->template->element = $this->element;
	}


	public function handleDelete() {
		if (!$this->element)
			$this->error();
		if (!$this->user->isAllowed($this->element, 'delete'))
			$this->forbidden();
		$this->element->delete();
		$this->flashMessage('Element was deleted.');
		$this->redirect('default');
	}


	public function createComponentNewElementControl() {
		return $this->newElementControlFactory->create($this->project);
	}


	public function createComponentElementControl() {
		if (!$this->element)
			$this->error();
		return $this->elementControlFactory->create($this->element);
	}


	protected function checkElement($id) {
		if ($id === NULL)
			$this->error();
		$element = $this->elementModel->get((int) $id);
		if (!$element)
			$this->error();
		if (!$this->user->isAllowed($element))
			$this->forbidden();
		return $element;
	}

}
