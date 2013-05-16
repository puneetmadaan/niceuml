<?php


class NoteControl extends ElementControl {


	protected function createComponentForm() {
		$form = parent::createComponentForm();
		unset($form['send']);

		$note = $this->elementModel->getByParent($this->element);
		$form->addTextarea('text', 'Text')
			->setDefaultValue($note->text);
		$form->addSubmit('send', 'Save');
		return $form;
	}


	public function formSucceeded($form) {
		$note = $this->elementModel->getByParent($this->element);
		$this->elementModel->save($note, $form->values);
		$this->presenter->flashMessage('Data saved.');
		$this->redirect('this');
	}

}
