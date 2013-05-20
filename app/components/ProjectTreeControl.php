<?php


/** Element and diagram list */
class ProjectTreeControl extends BaseControl
{

	/** @var Model\Entity\Project */
	protected $project;
	/** @var Model\ElementDAO */
	protected $elementModel;
	/** @var Model\DiagramDAO */
	protected $diagramModel;


	public function __construct(Model\ElementDAO $el, Model\DiagramDAO $di)
	{
		$this->elementModel = $el;
		$this->diagramModel = $di;
	}


	public function setProject(Model\Entity\Project $project)
	{
		$this->project = $project;
	}


	public function render()
	{
		$this->template->project = $this->project;
		$this->template->elements = $this->elementModel->findByProject($this->project)->order('name');
		$this->template->diagrams = $this->diagramModel->findByProject($this->project)->order('name');
		parent::render();
	}

}
