<?php

namespace ClassModule\Model;


class Association extends \Model\BaseChild implements \Model\ISourceModel {

	function load(\Model\Entity\Project $project, $name, $source){

	}


	function dump(\Model\Entity\Project $project){
		$result = array();
		$ids = $project->related('core_element')->collect('id');
		foreach ($this->table()->where('start_id', $ids)->where('end_id', $ids) as $assoc) {
			$result[$assoc->parent->name] = array(
				'name' => $assoc->parent->name,
				'type' => $assoc->parent->type,
				'start' => $assoc->parent->start->name,
				'end' => $assoc->parent->end->name,
				'direction' => $assoc->direction,
				'sourceRole' => $assoc->sourceRole,
				'sourceMultiplicity' => $assoc->sourceMultiplicity,
				'targetRole' => $assoc->targetRole,
				'targetMultiplicity' => $assoc->targetMultiplicity,
			);
		}
		return $result;
	}

}
