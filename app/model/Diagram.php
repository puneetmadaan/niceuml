<?php

namespace Model;


class Diagram extends Base implements ISourceModel {

	public function load(Entity\Project $project, $name, $source) {
		$diagram = $this->table()->where('project_id', $project->id)->where('name', $name)->fetch();

		if (isset($source['name']) && (!$diagram || $source['name'] !== $name)) {
			$dup = $this->table()->where('project_id', $project->id)->where('name', $source['name'])->fetch();
			if ($dup)
				throw new \SourceException("Duplicate diagram name " . $source['name']);
		}

		if (!$diagram) {
			$diagram = $this->create();
			$diagram->project_id = $project->id;
			if (!isset($source['type']))
				throw new \SourceException("Missing diagram type");
			$diagram->type = $source['type'];
		}
		$diagram->name = isset($source['name']) ? $source['name'] : $name;
		$diagram = $this->save($diagram);

		$placements = $diagram->related('core_placement');
		$placements->delete();
		foreach ($source['elements'] as $row) {
			$placements->insert(array(
				'element_id' => $row['element']->id,
				'posX' => (int) $row['posX'],
				'posY' => (int) $row['posY'],
			));
		}

		return $diagram;
	}


	public function dump(Entity\Project $project) {
		$result = array();
		foreach ($this->table()->where('project_id', $project->id) as $diagram) {
			$row = array(
				'name' => $diagram->name,
				'type' => $diagram->type,
				'elements' => array(),
			);
			foreach ($diagram->related('core_placement') as $el) {
				$row['elements'][$el->element->name] = array($el->posX, $el->posY);
			}
			$result[$diagram->name] = $row;
		}
		return $result;
	}
}
