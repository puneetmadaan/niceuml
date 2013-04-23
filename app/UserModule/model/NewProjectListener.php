<?php

namespace UserModule;

use Nette\Security;


class NewProjectListener extends \Nette\Object {

	protected $user;


	public function __construct(\Model\Project $projectModel, Security\User $user) {
		$this->user = $user;
		$projectModel->onCreate[] = $this->projectCreated;
	}


	public function projectCreated(\Model\Entity\Project $project) {
		if ($this->user->loggedIn)
			$project->related('user_project')->insert(array(
				'user_id' => $this->user->id,
				'role' => 'owner',
			));
	}


	public static function register(\Model\Project $projectModel, Security\User $user) {
		new self($projectModel, $user);
	}

}
