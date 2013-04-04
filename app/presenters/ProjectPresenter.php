<?php



class ProjectPresenter extends BasePresenter {
	
	protected $projects;
	protected $project;

	protected $users;

	public function injectProjects(Model\Project $projects) {
		$this->projects = $projects;
	}


	public function injectUsers(UserModule\Model $users) {
		$this->users = $users;
	}


	public function startup() {
		parent::startup();
		if (!$this->user->isAllowed('project'))
			$this->error(NULL, 403);
	}


	public function renderDefault() {
		$this->template->projects = $this->projects->table()
			->where('user_project:user_id = ?', $this->user->id);
	}


	public function actionEdit($id) {
		$project = $this->projects->get($id);

		if (!$project)
			$this->error();
		if (!$this->user->isAllowed($project))
			$this->error(NULL, 403);

		$this->project = $project;
	}

	public function actionNew() {
		$this->view = 'edit';
	}

	public function renderEdit() {
		$this->template->new = !$this->project;
		$this->template->project = $this->project;
	}


	public function createComponentProjectForm() {
		$form = $this->createForm();
		
		$form->addText('name', 'Project name', NULL, 30)
			->setRequired();


		$users = $this->users->table()->where('id != ?', $this->user->id)->fetchPairs('id', 'fullName');
		$form->addMultiSelect('users', 'Users', $users);


		$form->addSubmit('send');
		$form->onSuccess[] = $this->projectFormSucceeded;

		if ($this->project) {
			$form->defaults = $this->project;
			$form['users']->defaultValue = $this->project->related('user_project')->collect('user_id');
		}
		return $form;
	}


	public function projectFormSucceeded($form) {
		$values = $form->values;

		$users = $values->users;
		unset($values->users);
		
		$project = $this->projects->save($this->project, $values);
		
		if (!$this->project)
			$project->related('user_project')->insert(array(
				'user_id' => $this->user->id,
				'role' => 'owner',
			));
		else
			$project->related('user_project')->where('user_id != ? ', $this->user->id)
				->delete();

		foreach ($users as $u) {
			$project->related('user_project')->insert(array(
				'user_id' => $u,
			));
		}

		$this->flashMessage('Project saved.');
		$this->redirect('edit', $project->id);
	}

}