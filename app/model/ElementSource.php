<?php

namespace Model;

use SourceException,
	Nette,
	Nette\Utils\Strings;


class ElementSource extends Nette\Object
{

	protected $dao;
	protected $types;

	protected $models = array(); // type => ISourceModel


	public function __construct(ElementDAO $dao, ElementType $types)
	{
		$this->dao = $dao;
		$this->types = $types;
	}


	public function addType($name, ISourceModel $model)
	{
		if (isset($this->models[$name]))
			throw new Nette\InvalidArgumentException("Type '{$name}' already set.");
		$this->models[$name] = $model;
		return $this;
	}


	public function load(array $source, Entity\Project $project)
	{
		$table = $this->dao->findByProject($project, $this->types->get());
		$toDelete = array_fill_keys($table->collect('id'), TRUE);
		$elements = array();
		foreach ($table as $row)
			$elements[Strings::lower($row->name)] = $row;
		$newNames = array();
		$result = array();

		foreach ($source as $name => $el) {
			if (!is_array($el)) {
				if ($el instanceof NeonEntity)
					throw new SourceException("Unexpected entity in element '$name'.");
				$el = array('type' => $el);
			}

			$lName = Strings::lower($name);
			if (isset($elements[$lName])) {
				$old = $elements[$lName];
				unset($toDelete[$old->id]);
				$el['type'] = $old->type;
			}
			else {
				if (!isset($el['type']))
					throw new SourceException("Missing type in element '$name'.");
				if (!$this->types->has($el['type']))
					throw new SourceException("Invalid type '{$el['type']}'' in element '$name'.");
				$old = NULL;
			}

			if (!isset($el['name']))
				$el['name'] = $name;
			if (!$old || !Strings::compare($name, $el['name'])) {
				$newName = Strings::lower($el['name']);
				if (isset($elements[$newName]) || isset($newNames[$newName]))
					throw new SourceException("Duplicate name '$newName' in element '$name'.");
				$newNames[$newName] = TRUE;
			}

			if (isset($this->models[$el['type']])) {
				$model = $this->models[$el['type']];
				try {
					$result[$lName] = $model->load($el, $project, $old);
				} catch (SourceException $e) {
					throw new SourceException("Element '$name': " . $e->getMessage(), NULL, $e);
				}
			}
			else {
				if ($error = array_diff(array_keys($el), array('name', 'type')))
					throw new SourceException("Unknown field '" . implode("', '", $error) . "' in element " . $name);

				$el['project'] = $project;
				$result[$lName] = $this->dao->save($old, $el);
			}
		}

		// deletes children as well
		if ($toDelete)
			$this->dao->table()->where('id', array_keys($toDelete))->delete();

		return $result;
	}


	public function dump(Entity\Project $project)
	{
		$result = array();
		foreach ($this->dao->findByProject($project, $this->types->get()) as $el) {
			if (!$this->types->has($el->type))
				continue;
			if (isset($this->models[$el->type])) {
				$model = $this->models[$el->type];
				$row = $model->dump($el);
			}
			else {
				$row = array(
					'name' => $el->name,
					'type' => $el->type,
				);
			}
			if ($row !== NULL)
				$result[(string) $el->name] = $row;
		}

		return $result;
	}

}
