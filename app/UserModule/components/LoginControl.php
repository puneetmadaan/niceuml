<?php

namespace UserModule;

use BaseControl,
	ILoginControl,
	IFormFactory,
	Nette\Security;


class LoginControl extends BaseControl {

	/** @var Nette\Security\user */
	protected $user;

	/** @var FormFactory */
	protected $formFactory;


	public function __construct(Security\User $user, IFormFactory $formFactory) {
		$this->user = $user;
		$this->formFactory = $formFactory;
	}


	protected function createComponentLoginForm() {
		$form = $this->formFactory->create();
		$form->addText('login','E-mail')
			->setRequired();
		$form->addPassword('password','Password')
			->setRequired();
		$form->addSubmit('send', 'Log in');
		$form->onSuccess[] = $this->loginFormSucceeded;
		return $form;
	}


	public function loginFormSucceeded($form) {
		$values = $form->getValues();
		try {
			$this->user->login($values->login, $values->password);
		} catch (Security\AuthenticationException $e) {
			$form->addError($e->getMessage());
			return;
		}
		$this->redirect('this');
	}


	public function handleLogout() {
		if ($this->user->loggedIn) {
			$this->user->logout();
			$this->presenter->flashMessage('You have been logged out.');
		}
		$this->redirect('this');
	}

}
