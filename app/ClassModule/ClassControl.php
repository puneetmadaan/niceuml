<?php

namespace ClassModule;

use Nette\Utils\Neon;


class ClassControl extends \ElementControl {

	protected function createComponentForm() {
		$form = parent::createComponentForm();
		unset($form['send']);

		$class = $this->elementModel->getByParent($this->element);

		$form->addCheckbox('abstract', 'Abstract');
		$form->addCheckbox('static', 'Static');

		if ($class)
			$form->setDefaults($class);

		$form->addSubmit('send', 'Save');
		return $form;
	}


	public function formSucceeded($form) {
		$values = $form->values;

		$this->elementModel->save($this->elementModel->getByParent($this->element), $values);
		$this->presenter->flashMessage('Data saved.');
		$this->redirect('this');
	}

}
