<?php

namespace Model;

use SourceException,
	Nette,
	Nette\Utils\NeonEntity,
	Nette\Utils\Strings;


class DiagramSource extends Nette\Object
{

	protected $dao;
	protected $types = array();

	public function __construct(DiagramDAO $dao)
	{
		$this->dao = $dao;
	}


	public function addType($name)
	{
		if (isset($this->types[$name]))
			throw new Nette\InvalidArgumentException("Type '{$name}' already set.");
		$this->types[$name] = TRUE;
		return $this;
	}


	public function load(array $source, Entity\Project $project, $elements)
	{
		$table = $this->dao->findByProject($project, array_keys($this->types));
		$toDelete = array_fill_keys($table->collect('id'), TRUE);
		$diagrams = array();
		foreach ($table as $row)
			$diagrams[strtolower($row->name)] = $row;
		$newNames = array();
		$result = array();

		$known = array('name', 'type', 'elements');

		foreach ($source as $name => $d) {
			if (!is_array($d)) {
				if ($d instanceof NeonEntity)
					throw new SourceException("Unexpected entity in diagram '$name'.");
				$d = array('type' => $d);
			}

			if ($error = array_diff(array_keys($d), $known))
				throw new SourceException("Unknown key '" . implode("', '", $error) . "' in diagram '$name'.");

			$lName = Strings::lower($name);
			if (isset($diagrams[$lName])) {
				$old = $diagrams[$lName];
				unset($toDelete[$old->id]);
				$d['type'] = $old->type;
			}
			else {
				if (!isset($d['type']))
					throw new SourceException("Missing type in diagram '$name'.");
				if (!isset($this->types[$d['type']]))
					throw new SourceException("Invalid type '{$d['type']}'' in diagram '$name'.");
				$old = NULL;
				$d['project'] = $project;
			}

			if (!isset($d['name']))
				$d['name'] = $name;
			if (!$old || !Strings::compare($name, $d['name'])) {
				$newName = Strings::lower($d['name']);
				if (isset($diagrams[$newName]) || isset($newNames[$newName]))
					throw new SourceException("Duplicate name '{$newName}' in diagram '$name'.");
				$newNames[$newName] = TRUE;
			}

			$dElements = isset($d['elements']) ? $d['elements'] : array();
			if (!is_array($dElements))
				$dElements = array($dElements);
			unset($d['elements']);
			$result[$lName] = $diagram = $this->dao->save($old, $d);

			$placements = $diagram->related('core_placement');
			$placements->delete();
			$placedElements = array();

			foreach ($dElements as $key => $position) {
				if ($position instanceof NeonEntity) {
					$key = $position->value;
					$position = $position->attributes;
				}
				$el = Strings::lower($key);
				if (!isset($elements[$el]))
					throw new SourceException("Invalid element '$key' in diagram '$name'.");
				if (isset($placedElements[$el]))
					throw new SourceException("Duplicate element '$key' in diagram '$name'.");
				$placedElements[$el] = TRUE;

				$el = $elements[$el];
				if (!$this->dao->isElementTypeAllowed($diagram->type, $el->type)) // FIXME
					throw new SourceException("Invalid element type '{$el->type}' in diagram '$name', element '$key'.");

				if (count($position) !== 2)
					throw new SourceException("Invalid coordinate count in diagram '$name', element '$key'.");

				list($x, $y) = array_values($position);
				$placements->insert(array(
					'element_id' => $el->id,
					'posX' => (int) $x,
					'posY' => (int) $y,
				));
			}
		}

		if ($toDelete)
			$this->dao->table()->where('id', array_keys($toDelete))->delete();

		return $result;
	}


	public function dump(Entity\Project $project)
	{
		$result = array();
		foreach ($this->dao->findByProject($project, array_keys($this->types)) as $diagram) {
			if (!isset($this->types[$diagram->type]))
				continue;
			$row = array(
				'name' => $diagram->name,
				'type' => $diagram->type,
				'elements' => array(),
			);
			foreach ($diagram->related('core_placement') as $el) {
				$row['elements'][] = $ne = new NeonEntity;
				$ne->value = $el->element->name;
				$ne->attributes = array($el->posX, $el->posY);
			}
			$result[(string) $diagram->name] = $row;
		}
		return $result;
	}

}
