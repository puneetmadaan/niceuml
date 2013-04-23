<?php

namespace UserModule;

use Nette\Security;
use IFormFactory;

class LoginControl extends \BaseControl implements \IUserControl {

	/** @var Nette\Security\user */
	protected $user;

	/** @var FormFactory */
	protected $formFactory;


	public function __construct(Security\User $user, IFormFactory $formFactory) {
		$this->user = $user;
		$this->formFactory = $formFactory;
	}


	public function createComponentLoginForm() {
		$form = $this->formFactory->create();
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
			$this->user->login($values->login, $values->password);
		} catch (\Nette\Security\AuthenticationException $e) {
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
