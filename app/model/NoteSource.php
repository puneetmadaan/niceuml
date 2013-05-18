<?php

namespace Model;

use SourceException,
	Model\BaseChildDAO,
	Model\ISourceModel,
	Model\Entity\Element,
	Model\Entity\Project,
	Nette;


class NoteSource extends Nette\Object implements ISourceModel
{

	protected $dao;


	public function __construct(BaseChildDAO $dao)
	{
		$this->dao = $dao;
	}


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


	function dump($item)
	{
		if (!$item instanceof Element)
			return NULL;
		$note = $this->dao->getByParent($item);
		if (!$note)
			return NULL;

		$result = array(
			'name' => $note->name,
			'type' => $note->type,
		);

		return $result;
	}

}
