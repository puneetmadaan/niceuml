<?php

namespace Model;


class CommandModelList implements ICommandModel
{

	protected $models;


	public function add(ICommandModel $model)
	{
		$this->models[] = $model;
	}


	/**
	 * Parses and executes command from console.
	 * @param  string $command         command to execute
	 * @param  Entity\Project $project project to execute on
	 * @return bool                    was the command accepted?
	 */
	function execute($command, Entity\Project $project)
	{
		foreach ($this->models as $model) {
			if ($model->execute($command, $project))
				return TRUE;
		}

		return FALSE;
	}

}
