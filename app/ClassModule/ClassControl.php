<?php

namespace ClassModule;

use Nette\Utils\Neon;


class ClassControl extends \ElementControl {

	protected function createComponentForm() {
		$form = parent::createComponentForm();
		unset($form['send']);

		$form->addCheckbox('abstract', 'Abstract');
		$form->addCheckbox('static', 'Static');

		if ($this->element) {
			$class = $this->elementModel->getByParent($this->element);
			if ($class)
				$form->setDefaults($class);
		}

		$form->addSubmit('send', 'Save');
		return $form;
	}


	public function formSucceeded($form) {
		$values = $form->values;
		if (!$this->element) {
			if ($this->project)
				$values->project = $this->project;
			$values->type = 'class';
		}
		$class = $this->element ? $this->elementModel->getByParent($this->element) : NULL;
		$class = $this->elementModel->save($class, $values);
		$this->presenter->flashMessage('Data saved.');
		$this->presenter->redirect('edit', $class->id);
	}

}
