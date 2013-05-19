<?php

namespace Model\Entity;

use Nette;


class Element extends BaseEntity
{


	public function getCaption() {
		$name = (string) $this->getColumn('name');
		return ($name !== '' ? $name : "element #" . $this->id) . " (" . $this->type . ")";
	}


	/** @return Element self */
	public function setProject(Project $project) {
		$this->setColumn('project_id', (int) $project->id);
		return $this;
	}

}
