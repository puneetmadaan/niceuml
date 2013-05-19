<?php


class NoteControl extends ElementControl {


	protected function createComponentForm() {
		$form = parent::createComponentForm();
		unset($form['send']);

		$form->addTextarea('text', 'Text');
		$form->addSubmit('send', 'Save');

		if ($this->element) {
			$note = $this->elementModel->getByParent($this->element);
			if ($note)
				$form->setDefaults($note);
		}
		return $form;
	}


	public function formSucceeded($form) {
		$values = $form->values;
		$values->text = trim($values->text);
		if (!$this->element) {
			if ($this->project)
				$values->project = $this->project;
			$values->type = 'note';
		}
		$note = $this->element ? $this->elementModel->getByParent($this->element) : NULL;
		$note = $this->elementModel->save($note, $values);
		$this->presenter->flashMessage('Data saved.');
		$this->presenter->redirect('edit', $note->id);
	}

}
