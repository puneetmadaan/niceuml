<?php


class ProjectTreeControl extends BaseControl {

	/** @var Model\Entity\Project */
	protected $project;
	protected $elementModel;
	protected $diagramModel;


	public function __construct(Model\Entity\Project $project, Model\ElementDAO $el, Model\DiagramDAO $di = NULL) {
		$this->project = $project;
		$this->elementModel = $el;
		$this->diagramModel = $di;
	}


	public function render() {
		$this->template->project = $this->project;
		$this->template->elements = $this->elementModel->table()->where('project_id', $this->project->id)->order('name');
		$this->template->diagrams = $this->diagramModel->table()->where('project_id', $this->project->id)->order('name');
		parent::render();
	}

}
