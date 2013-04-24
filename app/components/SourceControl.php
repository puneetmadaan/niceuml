<?php


class SourceControl extends BaseControl {

	/** @var Model\Entity\Project */
	protected $project;


	public function __construct(Model\Entity\Project $project) {
		$this->project = $project;
	}

}
