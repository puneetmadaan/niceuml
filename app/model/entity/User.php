<?php

namespace Model\Entity;

use Nette,
	Nette\Security\Identity,
	Nette\Utils\Strings;


class User extends BaseEntity
{


	public function setPassword($password)
	{
		$this->setColumn('password', $this->calculateHash($password));
	}


	public function hasPassword($password)
	{
		return $this->getColumn('password') === $this->calculateHash($password, $this->getColumn('password'));
	}


	public function setEmail($email)
	{
		if (!Nette\Utils\Validators::isEmail($email))
			throw new Nette\InvalidArgumentException;
		$this->setColumn('email', $email);
		$this->login = $email;
		return $this;
	}


	public function getFullName()
	{
		return $this->name . ' ' . $this->surname;
	}


	public function resetPassword()
	{
		$password = Strings::random(10);
		$this->passwordNew = $this->calculateHash($password);
		$this->passwordNewCode = Strings::random(10);
		return $password;
	}


	public function resetPasswordConfirm()
	{
		$this->setColumn( 'password', $this->passwordNew );
		$this->passwordNew = $this->passwordNewCode = NULL;
		return $this;
	}


	public function createIdentity()
	{
		$data = $this->toArray();
		unset($data['password'], $data['passwordNew'], $data['passwordNewCode']);
		return new Identity($this->id, $this->role, $data);
	}


	/**
	 * Computes salted password hash.
	 * @param  string
	 * @return string
	 */
	protected function calculateHash($password, $salt = NULL)
	{
		if ($password === Strings::upper($password)) { // perhaps caps lock is on
			$password = Strings::lower($password);
		}
		return crypt($password, $salt ?: '$2a$07$' . Strings::random(22));
	}

}
