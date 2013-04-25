<?php

use Nette\Utils\Neon,
	Nette\Utils\NeonException;


class SourceControl extends BaseControl {

	/** @var Model\Entity\Project */
	protected $project;

	protected $formFactory;

	protected $elementTypes = array();
	protected $relationTypes = array();
	protected $diagramTypes = array();


	public function __construct(Model\Entity\Project $project, IFormFactory $formFactory) {
		$this->project = $project;
		$this->formFactory = $formFactory;
	}


	public function addElementType($name, ISourceModel $model) {
		$this->elementTypes[$name] = $model;
	}


	public function addRelationType($name, ISourceModel $model) {
		$this->relationTypes[$name] = $model;
	}


	public function addDiagramType($name, ISourceModel $model) {
		$this->diagramTypes[$name] = $model;
	}


	protected function createComponentForm() {
		$form = $this->formFactory->create();
		$form->addTextarea('source', 'Source', NULL, 10)
			->setRequired('Enter source.')
			->controlPrototype->addClass('span9');
		$form->addSubmit('send', 'Send');
		$form->onSuccess[] = $this->formSucceeded;
		return $form;
	}


	public function formSucceeded($form) {
		$source = $form['source']->value;
		try {
			$source = Neon::decode($source);
		} catch (NeonException $e) {
			$form->addError($e->getMessage());
			return;
		}
		$sections = array('elements' => TRUE, 'relations' => TRUE, 'diagrams' => TRUE);
		foreach ($source as $key => $value) {
			if (!isset($sections[$key])) {
				$form->addError('Unknown section "'.$key.'"');
				return;
			}
		}

		if (isset($source['elements']))
			foreach ((array) $source['elements'] as $element) {
				if (empty($element['type']) || empty($this->elementTypes[$element['type']])) {
					$form->addError('Invalid element type');
					return;
				}
				$this->elementTypes[$element['type']]->loadSource($element);
			}

		if (isset($source['relations']))
			foreach ($source['relations'] as $relation) {
				if (empty($relation['type']) || empty($this->relationTypes[$relation['type']])) {
					$form->addError('Invalid relation type');
					return;
				}
				$this->relationTypes[$relation['type']]->loadSource($relation);
			}

		if (isset($source['diagrams']))
			foreach ($source['diagrams'] as $diagram) {
				if (empty($diagram['type']) || empty($this->diagramTypes[$diagram['type']])) {
					$form->addError('Invalid diagram type');
					return;
				}
				$this->diagramTypes[$diagram['type']]->loadSource($diagram);
			}

		$this->presenter->flashMessage('Success', 'success');
		$this->redirect('this');
	}


	public function render() {
		$this['form']->render();
	}

}
