<?php

namespace ClassModule\Model;


class Association extends \Model\BaseChild implements \Model\ISourceModel {

	function load(\Model\Entity\Project $project, $name, $source){

	}


	function dump(\Model\Entity\Project $project){
		$result = array();
		$ids = $project->related('core_element')->collect('id');
		$table  = $this->parentModel->table()->where(array(
			'start_id' => $ids,
			'end_id' =>  $ids,
			'type' => 'association',
		));
		foreach ($table as $assoc) {

			$result[$assoc->name] = array(
				'name' => $assoc->name,
				'type' => $assoc->type,
				'start' => $assoc->start->name,
				'end' => $assoc->end->name,
				'direction' => $assoc->child->direction,
				'sourceRole' => $assoc->child->sourceRole,
				'sourceMultiplicity' => $assoc->child->sourceMultiplicity,
				'targetRole' => $assoc->child->targetRole,
				'targetMultiplicity' => $assoc->child->targetMultiplicity,
			);
		}
		return $result;
	}

}
