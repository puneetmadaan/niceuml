<?php

namespace ClassModule\Model;

use Model\ICommandModel,
	Model\Entity\Project,
	Nette;


class CommandModel extends Nette\Object implements ICommandModel
{

	protected $model;


	public function __construct(ClassDAO $model) {
		$this->model = $model;
	}


	public function execute($command, Project $project) {
		$input = Nette\Utils\Strings::trim($command);
		$words = Nette\Utils\Strings::split($input, "/\s+/");
		$name = array_shift($words);

		switch(strtolower($name)) {
			case 'create':
				$class = $this->model->create();
				$class->name = implode(' ', $words);
				$class->type = 'class';
				$class->project = $project;
				$class = $this->model->save($class);
				break;
			case 'delete':
				$class = $this->model->get($words[0]);
				if ($class)
					$this->model->delete($class);
				break;
			default:
				return FALSE;
		}
		return TRUE;
	}

}
