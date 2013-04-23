<?php

use Nette\Security\IAuthenticator,
	Nette\Security\AuthenticationException;


class DefaultAuthenticator extends Nette\Object implements IAuthenticator {


	public function authenticate(array $credentials) {
		list($username) = $credentials;
		throw new AuthenticationException("User '$username' not found.", self::IDENTITY_NOT_FOUND);
	}

}
