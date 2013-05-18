<?php


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter {

	/** @var FormFactory */
	protected $formFactory;

	/** @var MenuControlFactory */
	protected $menuControlFactory;


	public function injectBaseFactories(FormFactory $form, MenuControlFactory $menu) {
		$this->doInject('formFactory', $form);
		$this->doInject('menuControlFactory', $menu);
	}


	public function createForm() {
		return $this->formFactory->create();
	}


	protected function createComponentMenuControl() {
		return $this->menuControlFactory->create();
	}


	protected function doInject($name, $dependency) {
		if ($this->$name !== NULL)
			throw new Nette\InvalidStateException("Dependency '$name' has already been set.");
		$this->$name = $dependency;
	}


	public function forbidden($msg = NULL) {
		if (!$this->user->loggedIn) {
			$request = $this->storeRequest();
			$this->flashMessage('You need to be logged in to visit that page');
			$this->redirect(':Sign:in', $request);
		}
		else $this->error($msg, Nette\Http\IResponse::S403_FORBIDDEN);
	}

}
