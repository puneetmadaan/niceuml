<?php

namespace Model\Entity;

use Nette;


class Diagram extends BaseEntity
{


	/** @return Diagram self */
	public function setProject(Project $project) {
		$this->setColumn('project_id', (int) $project->id);
		return $this;
	}
}
