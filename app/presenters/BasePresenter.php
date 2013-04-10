<?php

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter {

	/** @var FormFactory */
	protected $formFactory;

	public function injectFormFactory(FormFactory $formFactory) {
		$this->doInject('formFactory', $formFactory);
	}


	public function createComponentLoginForm() {
		$form = $this->createForm();
		$form->addText('login','E-mail')
			->setRequired();
		$form->addPassword('password','Password')
			->setRequired();
		$form->addSubmit('send');
		$form->onSuccess[] = $this->loginFormSucceeded;
		return $form;
	}


	public function loginFormSucceeded($form) {
		$values = $form->getValues();
		try {
			$this->getUser()->login($values->login, $values->password);
		} catch (Nette\Security\AuthenticationException $e) {
			$form->addError($e->getMessage());
			return;
		}
		$this->redirect('this');
	}


	public function handleLogout() {
		if ($this->user->loggedIn) {
			$this->user->logout();
			$this->flashMessage('You have been logged out.');
		}
		$this->redirect('this');
	}



	protected function doInject($name, $dependency) {
		if ($this->$name !== NULL)
			throw new \Nette\InvalidStateException("Dependency '$name' has already been set.");
		$this->$name = $dependency;
	}


	public function createForm() {
		return $this->formFactory->createForm();
	}


}
