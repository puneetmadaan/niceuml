<?php

namespace Model;

use SourceException,
	Model\BaseChildDAO,
	Model\ISourceModel,
	Model\Entity\Element,
	Model\Entity\Project,
	Nette;


/** Notes source handler */
class NoteSource extends Nette\Object implements ISourceModel
{

	/** @var BaseChildDAO */
	protected $dao;


	public function __construct(BaseChildDAO $dao)
	{
		$this->dao = $dao;
	}


	/**
	 * Load one note ($source) into $project
	 * @param  array
	 * @param  Project
	 * @param  Element|NULL
	 * @return Element
	 */
	function load(array $source, Project $project, $original = NULL)
	{
		$known = array('name', 'type');
		if ($error = array_diff(array_keys($source), $known))
			throw new SourceException("Unknown field '" . implode("', '", $error));

		if (!isset($source['name']))
			throw new SourceException("Missing name.");
		$data = array(
			'name' => $source['name'],
			'project' => $project,
		);

		if ($original === NULL) {
			if (!isset($source['type']))
				throw new SourceException("Missing type.");
			$data['type'] = $source['type'];
		}
		else {
			if (!$original instanceof Element)
				throw new Nette\InvalidArgumentException;
			$original = $this->dao->getByParent($original);
		}

		$note = $this->dao->save($original, $data);
		return $note->getParent();
	}


	/**
	 * Dump one note into source
	 * @param  Element
	 * @return array
	 */
	function dump($item)
	{
		if (!$item instanceof Element)
			return NULL;

		$result = array(
			'name' => $item->name,
			'type' => $item->type,
		);

		return $result;
	}

}
