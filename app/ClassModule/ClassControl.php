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

		$form->addTextarea('attributes', 'Attributes')
			->setDefaultValue(Neon::encode($class->attributes, Neon::BLOCK));

		$form->addTextarea('operations', 'Operations')
			->setDefaultValue(Neon::encode($class->operations, Neon::BLOCK));

		$form->setDefaults($this->element->child);

		$form->addSubmit('send', 'Save');
		return $form;
	}


	public function formSucceeded($form) {
		$values = $form->values;

		$values->attributes = Neon::decode($values->attributes);
		$values->operations = Neon::decode($values->operations);

		$this->elementModel->save($this->element->child, $values);
		$this->presenter->flashMessage('Data saved.');
		$this->redirect('this');
	}

}
