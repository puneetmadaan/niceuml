<?php

namespace Model;

use SourceException,
	Nette,
	Nette\Utils\Strings;


/** Relation source handler */
class RelationSource extends Nette\Object
{

	/** @var RelationDAO */
	protected $dao;
	/** @var RelationType */
	protected $types;
	/** @var array of type => ISourceModel */
	protected $models = array();


	public function __construct(RelationDAO $dao, RelationType $types)
	{
		$this->dao = $dao;
		$this->types = $types;
	}


	/**
	 * @param  string
	 * @return self provides a fluent interface
	 */
	public function addType($name, ISourceModel $model)
	{
		if (isset($this->models[$name]))
			throw new Nette\InvalidArgumentException("Type '{$name}' already set.");
		$this->models[$name] = $model;
		return $this;
	}


	/**
	 * @param  array          source to load
	 * @param  Entity\Project project to load to
	 * @param  array          elements to use
	 * @return array          of relations
	 */
	public function load(array $source, Entity\Project $project, array $elements)
	{
		$result = array();
		$this->dao->findByElements($elements, $this->types->get())->delete();


		foreach ($source as $key => $rel) {
			if (!is_array($rel))
				throw new SourceException("Invalid value in relation $key.");

			if (!isset($rel['type']))
				throw new SourceException("Missing type in relation $key.");
			if (!$this->types->has($rel['type']))
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

			$ends = $this->types->getElementTypes($rel['type'], $rel['start']->type);
			if ($ends !== RelationType::ALL && !in_array($rel['end']->type, $ends))
				throw new SourceException("Incompatible elements in relation $key.");


			if (isset($this->models[$rel['type']])) {
				$model = $this->models[$rel['type']];
				try {
					$result[$key] = $model->load($rel, $project, NULL);
				} catch (SourceException $e) {
					throw new SourceException("Relation $key: " . $e->getMessage(), NULL, $e);
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


	/**
	 * @param  Entity\Project
	 * @return array
	 */
	public function dump(Entity\Project $project)
	{
		$result = array();
		foreach ($this->dao->findByProject($project, $this->types->get()) as $rel) {
			if (!$this->types->has($rel->type))
				continue;
			if (isset($this->models[$rel->type])) {
				$model = $this->models[$rel->type];
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
