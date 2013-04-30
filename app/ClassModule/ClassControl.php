<?php

namespace ClassModule;

use Nette\Utils\Neon;


class ClassControl extends \ElementControl {

	protected function createComponentForm() {
		$form = parent::createComponentForm();
		unset($form['send']);

		$class = $this->element->child;

		$form->addCheckbox('abstract', 'Abstract');
		$form->addCheckbox('static', 'Static');

		$form->setDefaults($this->element->child);

		$form->addSubmit('send', 'Save');
		return $form;
	}


	public function formSucceeded($form) {
		$values = $form->values;

		$this->elementModel->save($this->element->child, $values);
		$this->presenter->flashMessage('Data saved.');
		$this->redirect('this');
	}

}
