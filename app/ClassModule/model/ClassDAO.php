<?php

namespace ClassModule\Model;

use SourceException,
	Model\BaseChildDAO,
	Model\ISourceModel,
	Model\Entity\Element,
	Model\Entity\Project,
	Nette;


class ClassDAO extends BaseChildDAO implements ISourceModel
{


	function load(array $source, Project $project, $original = NULL)
	{
		$known = array('name', 'type', 'abstract', 'static');
		if ($error = array_diff(array_keys($source), $known))
			throw new SourceException("Unknown field '" . implode("', '", $error));

		if (!isset($source['name']))
			throw new SourceException("Missing name.");
		$data = array(
			'name' => $source['name'],
			'project' => $project,
			'abstract' => !empty($source['abstract']),
			'static' => !empty($source['static']),
		);

		if ($original === NULL) {
			if (!isset($source['type']))
				throw new SourceException("Missing type.");
			$data['type'] = $source['type'];
		}
		else {
			if (!$original instanceof Element)
				throw new Nette\InvalidArgumentException;
			$original = $this->getByParent($original);
		}

		$class = $this->save($original, $data);
		return $class->getParent();
	}


	function dump($item)
	{
		if (!$item instanceof Element)
			return NULL;
		$class = $this->getByParent($item);
		if (!$class)
			return NULL;

		$result = array(
			'name' => $class->name,
			'type' => $class->type,
		);

		if ($class->abstract)
			$result['abstract'] = TRUE;
		if ($class->static)
			$result['static'] = TRUE;

		return $result;
	}

}
