<?php

namespace ClassModule;

use Model\Command;


class Interpreter extends \Nette\Object implements \IInterpreter {

	protected $model;


	public function __construct(Model\ClassType $model) {
		$this->model = $model;
	}

	public function execute(Command $command, $project = NULL) {
		switch(strtolower($command->name)) {
			case 'create':
				$class = $this->model->create();
				$class->name = implode(' ', $command->args);
				$class->type = 'class';
				$class->project = $project;
				$class = $this->model->save($class);
				break;
			case 'delete':
				$class = $this->model->get($command->args[0]);
				if ($class)
					$this->model->delete($class);
				break;
			default:
		}
	}

}
