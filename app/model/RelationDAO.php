<?php

namespace Model;


class RelationDAO extends BaseDAO
{


	public function findByProject(Entity\Project $project, $types = NULL)
	{
		$table = $this->table()->where('start.project_id', $project->id);
		if ($types !== NULL)
			$table->where($this->tableName.'.type', $types);
		return $table;
	}


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


	public function findByElement(Entity\Element $element)
	{
		return $this->table->where('start_id = ? OR end_id = ?', $element->id, $element->id);
	}

}
