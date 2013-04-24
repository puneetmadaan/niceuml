<?php


class ProjectTreeControl extends BaseControl {

	/** @var Model\Entity\Project */
	protected $project;


	public function __construct(Model\Entity\Project $project) {
		$this->project = $project;
	}


	public function render() {
		$this->template->project = $this->project;
		parent::render();
	}

}
