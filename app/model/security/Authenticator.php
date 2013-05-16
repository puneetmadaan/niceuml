<?php

namespace Model\Security;

use Model\UserDAO,
	Nette,
	Nette\Security\IAuthenticator;


class Authenticator extends Nette\Object implements IAuthenticator
{

	protected $users;


	public function __construct(UserDAO $users)
	{
		$this->users = $users;
	}


	/**
	 * Performs an authentication.
	 * @return Nette\Security\Identity
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($login, $password) = $credentials;
		$user = $this->users->getByLogin($login);

		if (!$user) {
			throw new Nette\Security\AuthenticationException('No user with this e-mail exists.', self::IDENTITY_NOT_FOUND);
		}

		if (!$user->hasPassword($password)) {
			throw new Nette\Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
		}

		return $user->createIdentity();
	}

}
