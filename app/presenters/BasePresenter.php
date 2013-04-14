<?php

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter {

	/** @var FormFactory */
	protected $formFactory;

	/** @TODO: lazy ? */
	public function injectUserControl(IUserControl $userControl = NULL) {
		if ($userControl !== NULL)
			$this->addComponent($userControl, 'userControl');
	}


	public function injectFormFactory(FormFactory $formFactory) {
		$this->doInject('formFactory', $formFactory);
	}



	protected function doInject($name, $dependency) {
		if ($this->$name !== NULL)
			throw new \Nette\InvalidStateException("Dependency '$name' has already been set.");
		$this->$name = $dependency;
	}


	public function createForm() {
		return $this->formFactory->createForm();
	}


	public function forbidden($msg = NULL) {
		if (!$this->user->loggedIn) {
			$this->flashMessage('You need to be logged in to visit that page');
			$this->redirect(':Homepage:');
		}
		else $this->error($msg,403);
	}


}
