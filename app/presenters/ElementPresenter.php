<?php



class ElementPresenter extends ModellingPresenter {

	protected $element;

	public function actionDefault() {
	}

	public function renderDefault() {
		$this->template->elements = $this->project->related('element');
	}
	

	public function actionEdit($id) {
		$row = $this->project->related('core_element')->get($id);
		$this->element = $row->related($row->type)->fetch();
	}

	public function renderEdit() {

	}

	public function createComponentNewElementForm() {
		$form = $this->createForm();
		$items = array(
			'note' => 'Note',
			'class' => 'Class',
		);
		$form->addSelect('type', 'Type', $items)
			->setRequired();
		$form->addSubmit('send');
		$form->onSuccess[] = $this->newElementFormSucceeded;
		return $form;
	}

	public function newElementFormSucceeded($form) {
		$values = $form->values;
		$row = $this->project->related('core_element')->insert($values);
		if ($values->type === 'note')
			$row->related('core_note')->insert(NULL);
		elseif ($values->type === 'class')
			$row->related('class_class')->insert(NULL);
		$this->redirect('edit', $row->id);
	}

	public function createComponentElementControl() {
		$form = $this->createForm();
		$form->addTextarea('source','Source');
			//->defaultValue = Nette\Utils\Neon::encode( $this->element->toArray() + $this->element->ref('core_element', 'id')->toArray() );

		$form->addSubmit('send');
		$form->onSuccess[] = $this->elementControlSucceeded;
		
		return $form;
		/*
		if (!$this->element)
			return NULL;
		return $this->context->createElementControl($this->element->type);
		*/
	}

	public function elementControlSucceeded($form) {
		try {
			$source = Nette\Utils\Neon::decode( $form['source']->value );
		} catch ( Nette\Utils\NeonException $e ) {
			$form['source']->addError($e->getMessage());
			return;
		}
		if (isset($source['name'])) {
			$this->element->ref('core_element', 'id')->update(array(
				'name' => $source['name'],
			));
			unset($source['name']);
		}
		$this->element->update($source);
		$this->redirect('this');
	}

	public function createComponentNewRelationForm() {
		return $this->context->createNewElementForm();
	}
}
