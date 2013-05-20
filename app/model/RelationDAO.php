<?php

namespace Model;


/** Relation data access object */
class RelationDAO extends BaseDAO
{


	/**
	 * @param  Entity\Project
	 * @param  mixed    types to filter by
	 * @return Database\Selection
	 */
	public function findByProject(Entity\Project $project, $types = NULL)
	{
		$table = $this->table()->where('start.project_id', $project->id);
		if ($types !== NULL)
			$table->where($this->tableName.'.type', $types);
		return $table;
	}


	/**
	 * @param  array|Traversable
	 * @param  mixed    types to filter by
	 * @return Database\Selection
	 */
	public function findByElements($elements, $types = NULL)
	{
		$ids = array();
		foreach ($elements as $el) {
			if ($el instanceof Entity\Element)
				$ids[] = $el->id;
			else
				$ids[] = $el;
		}
		$table = $this->table()->where('start_id', $ids);
		if ($types !== NULL)
			$table->where('type', $types);
		return $table;
	}


	/** @return Database\Selection */
	public function findByElement(Entity\Element $element)
	{
		return $this->table()->where('start_id = ? OR end_id = ?', $element->id, $element->id);
	}

}
