<?php

namespace Model;

use SourceException,
	Nette,
	Nette\Utils\Strings;


class RelationSource extends Nette\Object
{

	protected $dao;
	protected $types = array(); // type => ISourceModel


	public function __construct(RelationDAO $dao)
	{
		$this->dao = $dao;
	}


	public function addType($name, ISourceModel $model = NULL)
	{
		if (isset($this->types[$name]))
			throw new Nette\InvalidArgumentException("Type '{$name}' already set.");
		$this->types[$name] = $model ?: FALSE;
		return $this;
	}


	public function load(Entity\Project $project, array $source, array $elements)
	{
		$result = array();
		$this->dao->findByElements($elements, array_keys($this->types))->delete();


		foreach ($source as $key => $rel) {
			if (!is_array($rel))
				$rel = array('type' => $rel);

			if (!isset($rel['type']))
				throw new SourceException("Missing type in relation $key.");
			if (!isset($this->types[$rel['type']]))
				throw new SourceException("Invalid type '{$rel['type']}' in relation $key.");

			if (!isset($rel['start']))
				throw new SourceException("Missing starting element in relation $key.");
			$start = Strings::lower($rel['start']);
			if (!isset($elements[$start]))
				throw new SourceException("Invalid starting element in relation $key.");
			$rel['start'] = $elements[$start];

			if (!isset($rel['end']))
				throw new SourceException("Missing ending element in relation $key.");
			$end = Strings::lower($rel['end']);
			if (!isset($elements[$end]))
				throw new SourceException("Invalid ending element in relation $key.");
			$rel['end'] = $elements[$end];

			// TODO: check element types


			if ($this->types[$rel['type']]) {
				$model = $this->types[$rel['type']];
				try {
					$result[$key] = $model->load($rel, $project, NULL);
				} catch (SourceException $e) {
					throw SourceException("Relation $key: " . $e->getMessage(), NULL, $e);
				}
			}
			else {
				$known = array('name', 'type', 'start', 'end');
				if ($error = array_diff(array_keys($rel), $known))
					throw new SourceException("Unknown field '" . implode("', '", $error) . "' in relation $key.");
				$result[$key] = $this->dao->save(NULL, $rel);
			}
		}

		return $result;
	}


	public function dump(Entity\Project $project)
	{
		$result = array();
		foreach ($this->dao->findByProject($project, array_keys($this->types)) as $rel) {
			if (!isset($this->types[$rel->type]))
				continue;
			if ($this->types[$rel->type]) {
				$model = $this->types[$rel->type];
				$row = $model->dump($rel);
			}
			else {
				$row = array(
					'name' => $rel->name,
					'type' => $rel->type,
					'start' => $rel->start->name,
					'end' => $rel->end->name,
				);
				if (!$row['name'])
					unset($row['name']);
			}
			if ($row !== NULL)
				$result[] = $row;
		}

		return $result;
	}

}
