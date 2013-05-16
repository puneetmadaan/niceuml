<?php

namespace ClassModule;


class ClassDiagramControl extends \BaseControl {

	protected $diagram;
	protected $elementModel;
	protected $relationModel;
	protected $formFactory;

	protected $rendered = FALSE;

	public function __construct(\Model\Entity\Diagram $diagram, \Model\Element $elementModel, \Model\Relation $relationModel, \IDiagramControlFactory $formFactory) {
		$this->diagram = $diagram;
		$this->elementModel = $elementModel;
		$this->relationModel = $relationModel;
		$this->formFactory = $formFactory;
	}

	protected function beforeRender() {
		if ($this->rendered) return;
		$this->rendered = TRUE;

		$this->template->diagram = $this->diagram;
		$this->template->placements = $placements = $this->diagram->related('placement');
		$ids = $placements->collect('element_id');
		if (count($ids)) {
			$this->template->elements = $this->elementModel->table()->where('id', $ids);
			$this->template->relations = $this->relationModel->table()->where(array(
				'start_id' => $ids,
				'end_id' => $ids,
			));
		}
		else {
			$this->template->elements = $this->template->relations = array();
		}
	}

	public function createComponentForm() {
		$oldType = $this->diagram->type;
		$this->diagram->type = NULL;
		$form = $this->formFactory->create($this->diagram);
		$this->diagram->type = $oldType;
		return $form;
	}

	public function render() {
		$this->beforeRender();
		$this->template->mode = NULL;
		parent::render();
	}


	public function renderScripts() {
		$this->beforeRender();
		$this->template->mode = 'scripts';
		parent::render();
	}
}
