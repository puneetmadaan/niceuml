<?php

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter {


	public function createComponentMenuControl() {
		return $this->context->createMenuControl();
	}


	public function createComponentLoginControl() {
		return $this->context->createLoginControl();
	}


	protected function doInject($name, $dependency) {
		if ($this->$name !== NULL)
			throw new \Nette\InvalidStateException("Dependency '$name' has already been set.");
		$this->$name = $dependency;
	}


	public function createForm() {
		return $this->context->createForm();
	}


	public function forbidden($msg = NULL) {
		if (!$this->user->loggedIn) {
			$this->flashMessage('You need to be logged in to visit that page');
			$this->redirect(':Homepage:');
		}
		else $this->error($msg,403);
	}

}
