<?php

namespace ClassModule\Model;

use CommandException,
	Model\ICommandModel,
	Model\ElementDAO,
	Model\DiagramDAO,
	Model\PlacementDAO,
	Model\Entity\Project,
	Nette,
	Nette\Utils\Strings;


/**
 * Class for command interpreting
 */
class CommandModel extends Nette\Object implements ICommandModel
{

	/** @var ElementDAO */
	protected $elements;
	/** @var ClassDAO */
	protected $classes;
	/** @var DiagramDAO */
	protected $diagrams;
	/** @var PlacementDAO */
	protected $placements;


	public function __construct(ElementDAO $elements, ClassDAO $classes, DiagramDAO $diagrams, PlacementDAO $placements)
	{
		$this->elements = $elements;
		$this->classes = $classes;
		$this->diagrams = $diagrams;
		$this->placements = $placements;
	}


	/**
	 * Parses and executes command from console.
	 * @param  string  command to execute
	 * @param  Project project to execute on
	 * @return bool    was the command accepted?
	 */
	public function execute($command, Project $project)
	{
		$command = Strings::trim($command);
		$command = Strings::replace($command, '/\\s+/', ' ');

		if ($match = Strings::match($command, '/^CREATE CLASS (.*)$/i')) {
			$row = $this->elements->findByProject($project)->where('name', $match[1])->fetch();
			if ($row)
				throw new CommandException("Element with name '{$match[1]}' already exists.");
			$this->classes->save(NULL, array(
				'name' => $match[1],
				'project' => $project,
				'type' => 'class',
			));
			return TRUE;
		}
		elseif ($match = Strings::match($command, '/^DELETE CLASS (.*)$/i')) {
			$class = $this->getClass($project, $match[1]);
			$this->elements->delete($class);
			return TRUE;
		}
		elseif ($match = Strings::match($command, '/^PLACE (.*) ON (.*[^ ]) ?\( ?(\\d+) ?, ?(\\d+) ?\)$/i')) {
			$class = $this->getClass($project, $match[1]);
			$diagram = $this->getDiagram($project, $match[2]);
			$place = $this->placements->table()->where(array(
					'element_id' => $class->id,
					'diagram_id' => $diagram->id,
				))->fetch() ?: NULL;
			$this->placements->save($place, array(
					'element_id' => $class->id,
					'diagram_id' => $diagram->id,
					'posX' => (int) $match[3],
					'posY' => (int) $match[4],
				));
			return TRUE;
		}
		elseif ($match = Strings::match($command, '/^REMOVE (.*) FROM (.*)$/i')) {
			$class = $this->getClass($project, $match[1]);
			$diagram = $this->getDiagram($project, $match[2]);
			$row = $this->placements->table()->where(array(
					'element_id' => $class->id,
					'diagram_id' => $diagram->id,
				))->fetch();
			if (!$row)
				throw new CommandException("Class '{$match[1]}' was not on diagram '{$match[2]}'.");
			$this->placements->delete($row);
			return TRUE;
		}

		return FALSE;
	}


	private function getClass(Project $project, $name)
	{
		$class = $this->elements->findByProject($project)
				->where('name', $name)
				->where('type', 'class')
				->fetch();
		if (!$class)
			throw new CommandException("Class '$name' not found.");
		return $class;
	}


	private function getDiagram(Project $project, $name)
	{
		$diagram = $this->diagrams->findByProject($project)
				->where('name', $name)
				->where('type', 'class')
				->fetch();
		if (!$diagram)
			throw new CommandException("Diagram '$name' not found.");
		return $diagram;
	}

}
