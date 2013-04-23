<?php

namespace UserModule;

use Nette,
	Nette\Security;


class Authenticator extends Nette\Object implements Security\IAuthenticator {

	protected $users;


	public function __construct(Model $users) {
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
		$user = $this->users->table()->where('login', $login)->fetch();

		if (!$user) {
			throw new Security\AuthenticationException('No user with this e-mail exists.', self::IDENTITY_NOT_FOUND);
		}

		if (!$user->hasPassword($password)) {
			throw new Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
		}

		return $user->createIdentity();
	}

}
