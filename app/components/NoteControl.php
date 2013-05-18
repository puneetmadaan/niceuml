<?php


class NoteControl extends ElementControl {


	protected function createComponentForm() {
		$form = parent::createComponentForm();
		unset($form['send']);

		$form->addTextarea('text', 'Text');
		$form->addSubmit('send', 'Save');

		$note = $this->elementModel->getByParent($this->element);
		$form->setDefaults($note);
		return $form;
	}


	public function formSucceeded($form) {
		$values = $form->values;
		$values->text = trim($values->text);
		$note = $this->elementModel->getByParent($this->element);
		$this->elementModel->save($note, $values);
		$this->presenter->flashMessage('Data saved.');
		$this->redirect('this');
	}

}
