<?php


namespace UserModule;



class DefaultPresenter extends \BasePresenter {
	
	protected $users;
	protected $userDetail;

	public function injectUsers(Model $users) {
		$this->users = $users;
	}


	public function actionDefault() {
		if ($this->user->loggedIn) {
			$user = $this->users->get($this->user->id);
			if (!$user)
				$this->error('Unknown user logged in.');
			$this->userDetail = $user;
		}
	}

	public function createComponentUserForm() {
		$form = $this->createForm();
		$users = $this->users;
		if (!$this->user->loggedIn) {
			$form->addText('email', 'E-mail', NULL, 100)
				->setRequired()
				->addRule($form::EMAIL)
				->addRule(function ($input) use ($users) {
					return $users->isLoginUnique($input->value);
				}, 'This e-mail is already used.');
			$form->addPassword('password', 'Password')
				->setRequired()
				->addRule($form::MIN_LENGTH, NULL, 6);
			$form->addPassword('passwordConfirm', 'Password again')
				->setRequired()
				->addRule($form::EQUAL, 'Passwords do not match.', $form['password']);
		}
		$form->addText('name', 'Name', NULL, 20)
			->setRequired();
		$form->addText('surname', 'Surname', NULL, 30)
			->setRequired();
		$form->addText('nick', 'Nickname', NULL, 20);

		if ($this->user->loggedIn) {
			$user = $this->userDetail;
			$old = $form->addPassword('passwordOld', 'Old password');
			$form->addPassword('password', 'New password')
				->addCondition($form::FILLED)
				->addRule($form::MIN_LENGTH, NULL, 6);
			$form->addPassword('passwordConfirm', 'New password again')
				->addConditionOn($form['password'], $form::FILLED)
				->addRule($form::FILLED)
				->addRule($form::EQUAL, NULL, $form['password']);
			$old->addConditionOn($form['password'], $form::FILLED)
				->addRule($form::FILLED)
				->addRule(function ($input) use ($user) {
					return $user->hasPassword($input->value);
				}, 'Incorrect password.');
		}

		$form->addSubmit('send');
		$form->onSuccess[] = $this->userFormSucceeded;

		if ($this->user->loggedIn) {
			$form->defaults = $this->user->identity->data;
		}
		return $form;
	}


	public function userFormSucceeded($form) {
		$values = $form->values;
		unset($values->passwordConfirm, $values->passwordOld);
		if (!$values->password)
			unset($values->password);
		$user = $this->user->loggedIn ? $this->userDetail : NULL;
		$user = $this->users->save( $user, $values );
		if ($this->user->loggedIn) {
			foreach ($user->createIdentity()->data as $key => $value)
				$this->user->identity->$key = $value;
		}
		$this->flashMessage($this->user->loggedIn ? 'Data saved.' : 'Registration successful.');
		$this->redirect('this');
	}

}