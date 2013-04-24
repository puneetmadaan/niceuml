<?php

namespace Model\Entity;


class Element extends BaseParent {


	public function setChild(BaseChild $child) {
		if (!$child instanceof ElementChild)
			throw new \Nette\InvalidArgumentException;
		return parent::setChild($child);
	}


	/** @return Element self */
	public function setProject(Project $project) {
		$this->setColumn('project_id', (int) $project->id);
		return $this;
	}


	/** @return Element self */
	public function setPackage(Package $package) {
		$this->setColumn('package_id', (int) $package->id);
		return $this;
	}

}
