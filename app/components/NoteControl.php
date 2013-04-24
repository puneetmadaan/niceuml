<?php


class NoteControl extends ElementControl {


	protected function createComponentForm() {
		$form = parent::createComponentForm();
		unset($form['send']);

		$form->addTextarea('text', 'Text')
			->setDefaultValue($this->element->child->text);
		$form->addSubmit('send', 'Save');
		return $form;
	}


	public function formSucceeded($form) {
		$this->elementModel->save($this->element->child, $form->values);
		$this->presenter->flashMessage('Data saved.');
		$this->redirect('this');
	}

}
