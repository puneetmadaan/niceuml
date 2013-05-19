<?php


class ProjectTreeControl extends BaseControl {

	/** @var Model\Entity\Project */
	protected $project;
	protected $elementModel;
	protected $diagramModel;


	public function __construct(Model\ElementDAO $el, Model\DiagramDAO $di) {
		$this->elementModel = $el;
		$this->diagramModel = $di;
	}


	public function setProject(Model\Entity\Project $project)
	{
		$this->project = $project;
	}


	public function render() {
		$this->template->project = $this->project;
		$this->template->elements = $this->elementModel->table()->where('project_id', $this->project->id)->order('name');
		$this->template->diagrams = $this->diagramModel->table()->where('project_id', $this->project->id)->order('name');
		parent::render();
	}

}
