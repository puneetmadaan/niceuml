<?php

use Model\ISourceModel,
	Nette\Utils\Neon,
	Nette\Utils\NeonEntity,
	Nette\Utils\NeonException;


class SourceControl extends BaseControl {

	/** @var Model\Entity\Project */
	protected $project;

	protected $formFactory;

	protected $diagramModel;

	protected $db;

	protected $elementTypes = array();
	protected $relationTypes = array();
	protected $diagramTypes = array();


	public function __construct(Model\Entity\Project $project, IFormFactory $formFactory, Model\Diagram $diagramModel, Nette\Database\Connection $db) {
		$this->project = $project;
		$this->formFactory = $formFactory;
		$this->diagramModel = $diagramModel;
		$this->db = $db;
	}


	public function addElementType($name, ISourceModel $model) {
		if (isset($this->elementTypes[$name]))
			throw new \Nette\InvalidArgumentException("Element type '{$name}' already set.");
		$this->elementTypes[$name] = $model;
		return $this;
	}


	public function addRelationType($name, ISourceModel $model) {
		if (isset($this->relationTypes[$name]))
			throw new \Nette\InvalidArgumentException("Relation type '{$name}' already set.");
		$this->relationTypes[$name] = $model;
		return $this;
	}


	public function addDiagramType($name) {
		if (isset($this->diagramTypes[$name]))
			throw new \Nette\InvalidArgumentException("Diagram type '{$name}' already set.");
		$this->diagramTypes[$name] = TRUE;
		return $this;
	}


	protected function createComponentForm() {
		$form = $this->formFactory->create();
		$form->addTextarea('source', 'Source', NULL, 10);
		$form->addSubmit('send', 'Send');
		$form->onSuccess[] = $this->formSucceeded;
		return $form;
	}


	public function formSucceeded($form) {
		$source = $form['source']->value;
		try {
			$source = (array) Neon::decode($source);
		} catch (NeonException $e) {
			$form->addError($e->getMessage());
			return;
		}

		$this->db->beginTransaction();
		try {
			$sections = array('elements' => TRUE, 'relations' => TRUE, 'diagrams' => TRUE);
			foreach ($source as $key => $value) {
				if (!isset($sections[$key])) {
					$form->addError('Unknown section "'.$key.'"');
					$this->db->rollback();
					return;
				}
			}

			$source += array('elements' => array(), 'relations' => array(), 'diagrams' => array());
			$elements = array();
			$oldElements = $this->project->related('core_element')->fetchPairs('name');
			foreach ((array) $source['elements'] as $name => $element) {
				if (empty($element['type']) || empty($this->elementTypes[$element['type']])) {
					$form->addError('Invalid element type in element ' . $name);
					$this->db->rollback();
					return;
				}
				$elements[$name] = $this->elementTypes[$element['type']]->load($this->project, $name, $element);
				unset($oldElements[$name]);
			}
			$this->project->related('core_element')->where('id', array_values($oldElements))->delete();


			$els = $this->project->related('core_element')->collect('id');
			$this->db->table('core_relation')->where('start_id', $els)->delete();

			foreach ((array) $source['relations'] as $name => $relation) {
				if (empty($relation['type']) || empty($this->relationTypes[$relation['type']])) {
					$form->addError('Invalid relation type in relation ' . $name);
					$this->db->rollback();
					return;
				}
				if (!isset($relation['start'], $elements[$relation['start']])) {
					$form->addError('Invalid starting element in relation ' . $name);
					$this->db->rollback();
					return;
				}
				if (!isset($relation['end'], $elements[$relation['end']])) {
					$form->addError('Invalid ending element in relation ' . $name);
					$this->db->rollback();
					return;
				}
				$relation['start'] = $elements[$relation['start']];
				$relation['end'] = $elements[$relation['end']];
				$this->relationTypes[$relation['type']]->load($this->project, $name, $relation);
			}

			foreach ($source['diagrams'] as $name => $diagram) {
				if (empty($diagram['type']) || empty($this->diagramTypes[$diagram['type']])) {
					$form->addError('Invalid diagram type in diagram ' . $name);
					$this->db->rollback();
					return;
				}
				$placements = array();
				if (isset($diagram['elements'])) {
					foreach ($diagram['elements'] as $el => $position) {
						if ($position instanceof NeonEntity) {
							$el = $position->value;
							$position = $position->attributes;
							// TODO: check for duplicates
						}
						if (empty($elements[$el])) {
							$form->addError('Invalid element "' . $el . '" in diagram ' . $name);
							$this->db->rollback();
							return;
						}
						if (count($position) !== 2) {
							$form->addError('Invalid coordinate count in diagram ' . $name);
						}
						list($x, $y) = array_values($position);
						$placements[] = array(
							'element' => $elements[$el],
							'posX' => $x,
							'posY' => $y,
						);
					}
				}
				$diagram['elements'] = $placements;
				$this->diagramModel->load($this->project, $name, $diagram);
			}
			$this->db->commit();
		} catch (SourceException $e) {
			$this->db->rollback();
			$form->addError($e->getMessage());
			return;
		} catch (Exception $e) {
			$this->db->rollback();
			throw $e;
			// Nette\Diagnostics\Debugger::log($e);
			// $form->addError("Unknown error occured.");
			return;
		}

		$this->presenter->flashMessage('Source loaded.', 'success');
		$this->redirect('this');
	}


	public function render($mode = NULL) {
		if (!$this['form']->submitted) {
			$source = array(
				'elements' => array(),
				'relations' => array(),
				'diagrams' => $this->diagramModel->dump($this->project),
			);
			foreach ($this->elementTypes as $key => $model)
				$source['elements'] += (array) $model->dump($this->project);
			foreach ($this->relationTypes as $key => $model)
				$source['relations'] += (array) $model->dump($this->project);
			$this['form']['source']->defaultValue = Neon::encode($source, Neon::BLOCK);
		}
		$this->template->mode = $mode;
		parent::render();
	}

}


class SourceException extends Nette\InvalidStateException
{
}
