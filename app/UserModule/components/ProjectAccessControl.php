<?php

namespace UserModule;

use Nette\Application\UI;


class ProjectAccessControl extends UI\Control {

	protected $formFactory;


	public function __construct(IFormFactory $formFactory) {
		$this->formFactory = $formFactory;
	}


	public function render() {
		throw new \Exception;
	}

}
