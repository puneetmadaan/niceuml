<?php

namespace Model;

use Nette\Utils\NeonEntity;


/** Diagram data access object */
class DiagramDAO extends BaseDAO
{


	/**
	 * @param  Entity\Project
	 * @param  mixed    types to filter by
	 * @return Database\Selection
	 */
	public function findByProject(Entity\Project $project, $types = NULL)
	{
		$table = $this->table()->where('project_id', $project->id);
		if ($types !== NULL)
			$table->where('type', $types);
		return $table;
	}

}
