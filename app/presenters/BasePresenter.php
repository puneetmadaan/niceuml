<?php


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter {

	/** @var IFormFactory */
	protected $formFactory;

	/** @var IMenuControlFactory */
	protected $menuControlFactory;

	/** @var ILoginControlFactory */
	protected $loginControlFactory;


	public function injectBaseFactories(IFormFactory $form, IMenuControlFactory $menu, ILoginControlFactory $login = NULL) {
		$this->doInject('formFactory', $form);
		$this->doInject('menuControlFactory', $menu);
		if ($login !== NULL)
			$this->doInject('loginControlFactory', $login);
	}


	protected function beforeRender() {
		parent::beforeRender();
		$this->template->isLoginControl = $this->loginControlFactory !== NULL;
	}


	public function createForm() {
		return $this->formFactory->create();
	}


	public function createComponentMenuControl() {
		return $this->menuControlFactory->create();
	}


	public function createComponentLoginControl() {
		if ($this->loginControlFactory === NULL)
			throw new Nette\InvalidArgumentException("No factory for component 'loginControl' has been set.");
		return $this->loginControlFactory->create();
	}


	protected function doInject($name, $dependency) {
		if ($this->$name !== NULL)
			throw new \Nette\InvalidStateException("Dependency '$name' has already been set.");
		$this->$name = $dependency;
	}


	public function forbidden($msg = NULL) {
		if (!$this->user->loggedIn) {
			$this->flashMessage('You need to be logged in to visit that page');
			$this->redirect(':Homepage:');
		}
		else $this->error($msg, Nette\Http\IResponse::S403_FORBIDDEN);
	}

}
