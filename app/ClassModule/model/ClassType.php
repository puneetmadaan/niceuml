<?php

namespace ClassModule\Model;


class ClassType extends \Model\BaseChildDAO implements \Model\ISourceModel {

	function load(\Model\Entity\Project $project, $name, $source){
		$class = $this->parentModel->table()->where('project_id', $project->id)->where('name', $name)->fetch();

		if (isset($source['name']) && (!$class || $source['name'] !== $name)) {
			$dup = $this->parentModel->table()->where('project_id', $project->id)->where('name', $source['name'])->fetch();
			if ($dup)
				throw new \SourceException("Duplicate project name " . $source['name']);
		}

		if (!$class) {
			$class = $this->create();
			$class->project_id = $project->id;
			if (!isset($source['type']))
				throw new \SourceException("Missing project type");
			$class->type = $source['type'];
		}
		else $class = $this->getByParent($class);

		$class->name = isset($source['name']) ? $source['name'] : $name;
		if (array_key_exists('abstract', $source))
			$class->abstract = (bool) $source['abstract'];
		if (array_key_exists('static', $source))
			$class->static = (bool) $source['static'];
		$class = $this->save($class);

		return $class->getParent();
	}


	function dump(\Model\Entity\Project $project){
		$result = array();
		$table  = $this->parentModel->table()->where(array(
			'project_id' => $project->id,
			'type' => 'class',
		));

		foreach ($table as $class) {
			$class = $this->getByParent($class);
			if (!$class)
				continue;

			$result[$class->name] = array(
				'name' => $class->name,
				'type' => $class->type,
			);
			if ($class->abstract)
				$result[$class->name]['abstract'] = TRUE;
			if ($class->static)
				$result[$class->name]['static'] = TRUE;

		}
		return $result;
	}

}
