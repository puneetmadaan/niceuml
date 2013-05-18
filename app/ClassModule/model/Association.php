<?php

namespace ClassModule\Model;

use SourceException,
	Model\BaseChildDAO,
	Model\ISourceModel,
	Model\Entity\relation,
	Model\Entity\Project,
	Nette;


class Association extends BaseChildDAO implements ISourceModel
{


	public function load(array $source, Project $project, $original = NULL)
	{

		$known = array('name', 'type', 'start', 'end', 'direction', 'sourceRole', 'sourceMultiplicity', 'targetRole', 'targetMultiplicity');
		if ($error = array_diff(array_keys($source), $known)) {
			throw new SourceException("Unknown field '" . implode("', '", $error));
		}

		$data = array();
		foreach ($known as $key)
			if (array_key_exists($key, $source))
				$data[$key] = $source[$key];

		return $this->save($original, $data);
	}


	public function dump($item)
	{
		if (!$item instanceof Relation)
			return NULL;
		$assoc = $this->getByParent($item);

		$result = array(
			'name' => $assoc->name,
			'type' => $assoc->type,
			'start' => $assoc->start->name,
			'end' => $assoc->end->name,
			'direction' => $assoc->direction,
			'sourceRole' => $assoc->sourceRole,
			'sourceMultiplicity' => $assoc->sourceMultiplicity,
			'targetRole' => $assoc->targetRole,
			'targetMultiplicity' => $assoc->targetMultiplicity,
		);
		return array_filter($result);
	}

}
