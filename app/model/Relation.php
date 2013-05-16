<?php

namespace Model;


class Relation extends Base
{


	public function findByProject(Entity\Project $project, $types = NULL)
	{
		$table = $this->table()->where('start.project_id', $project->id);
		if ($types !== NULL)
			$table->where('type', $types);
		return $table;
	}


	public function findByElement(Entity\Element $element)
	{
		return $this->table->where('start_id = ? OR end_id = ?', $element->id, $element->id);
	}

}
