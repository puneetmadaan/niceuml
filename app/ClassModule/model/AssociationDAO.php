<?php

namespace ClassModule\Model;

use SourceException,
	Model\BaseChildDAO,
	Model\ISourceModel,
	Model\Entity\Relation,
	Model\Entity\Project,
	Nette;


/**
 * Associations data access object
 */
class AssociationDAO extends BaseChildDAO implements ISourceModel
{


	/**
	 * Load one association ($source) into $project
	 * @param  array
	 * @param  Project
	 * @param  Relation|NULL
	 * @return Relation
	 */
	public function load(array $source, Project $project, $original = NULL)
	{

		$known = array('name', 'type', 'start', 'end', 'direction', 'sourceRole', 'sourceMultiplicity', 'targetRole', 'targetMultiplicity');
		if ($error = array_diff(array_keys($source), $known)) {
			throw new SourceException("Unknown field '" . implode("', '", $error)."'.");
		}

		$data = array();
		foreach ($known as $key)
			if (array_key_exists($key, $source))
				$data[$key] = $source[$key];

		if ($original !== NULL) {
			if (!$original instanceof Relation)
				throw new Nette\InvalidArgumentException;
			$original = $this->getByParent($original);
		}
		if ($original === NULL) {
			if (!isset($data['type']))
				throw new SourceException("Missing type.");
		}
		else unset($data['type']);

		return $this->save($original, $data)->getParent();
	}


	/**
	 * Dump one association into source
	 * @param Relation
	 * @return array
	 */
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
		return array_filter($result, function($val) {
			return (string) $val !== '';
		});
	}

}
