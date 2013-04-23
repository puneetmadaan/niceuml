<?php



class _FormFactory extends Nette\Object {


	protected $formCallback;


	public function __construct($formCallback) {
		$this->formCallback = $formCallback;
	}


	public function createForm() {
		return $this->formCallback->invoke();
	}

}
