<?php



class ElementPresenter extends ModellingPresenter {

	protected $element;

	public function actionDefault() {
	}

	public function renderDefault() {
		$this->template->elements = $this->project->related('element');
	}
	

	public function actionEdit($id) {

	}

	public function renderEdit() {

	}

	public function createComponentNewElementForm() {
		return $this->context->createNewElementForm();
	}

	public function createComponentElementControl() {
		if (!$this->element)
			return NULL;
		return $this->context->createElementControl($this->element->type);
	}

	public function createComponentNewRelationForm() {
		return $this->context->createNewElementForm();
	}
}
