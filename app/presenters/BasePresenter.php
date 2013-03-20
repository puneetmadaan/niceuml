<?php

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter {


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

	public function createForm() {
		return new Nette\Application\UI\Form;
	}


}
