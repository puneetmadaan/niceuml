<?php

/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter {

	protected $projectModel;


	public function injectProjectModel(Model\Project $projectModel) {
		$this->doInject('projectModel', $projectModel);
	}


	public function renderDefault() {
		$projects = $this->projectModel->table();
		$this->projectModel->filterAllowed($this->user, $projects);
		$this->template->projects = $projects;
	}


	/*

	protected $diagram;

	public function renderDefault()
	{
		if (!isset($this->diagram['classes']))
			$this->diagram['classes'] = array();
		if (!isset($this->diagram['associations']))
			$this->diagram['associations'] = array();
		$this->template->diagram = $this->diagram;
	}


	public function createComponentDiagramForm() {
		$form = new \Nette\Application\UI\Form;
		$form->addTextarea('text')->defaultValue = <<<EOT
classes:
  Publication: []
  Book:
    parent: Publication
    attributes:
      +name: string
      -isbn: string
      - ~package
      - '#protected'
    operations:
      +getISBN: string
      +setISBN: Book(isbn: string)
  Author:
    attributes:
      - name
      - birthDate
associations:
  - [Book: *, Author: 1]
EOT;
		$form->addSubmit('send');
		$form->onSuccess[] = $this->diagramFormSucceeded;
		return $form;
	}


	public function diagramFormSucceeded($form) {
		$values = $form->values;
		try {
			$this->diagram = \Nette\Utils\Neon::decode($values->text);
		} catch(\Nette\Utils\NeonException $e) {
			$form->addError($e->getMessage());
		}
	} */

}
