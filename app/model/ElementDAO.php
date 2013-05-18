<?php

namespace Model;


class ElementDAO extends BaseDAO
{


	public function findByProject(Entity\Project $project, $types = NULL)
	{
		$table = $this->table()->where('project_id', $project->id);
		if ($types !== NULL)
			$table->where('type', $types);
		return $table;
	}

}
