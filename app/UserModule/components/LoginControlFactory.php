<?php

namespace UserModule;


class LoginControlFactory implements \ILoginControlFactory {

	private $container;


	public function __construct(\Nette\DI\Container $container) {
		$this->container = $container;
	}


	public function create() {
		return $this->container->createUserModule__loginControl();
	}

}
