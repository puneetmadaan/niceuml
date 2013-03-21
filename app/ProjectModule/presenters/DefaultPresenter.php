<?php


namespace ProjectModule;



class DefaultPresenter extends \BasePresenter {
	
	protected $projects;
	protected $project;

	public function injectProjects(Model $projects) {
		$this->projects = $projects;
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


		$form->addSubmit('send');
		$form->onSuccess[] = $this->projectFormSucceeded;

		if ($this->project) {
			$form->defaults = $this->project;
		}
		return $form;
	}


	public function projectFormSucceeded($form) {
		$values = $form->values;
		if (!$this->project)
			$values->user_id = $this->user->id;
		
		$project = $this->projects->save($this->project, $values);
		
		if (!$this->project)
			$project->related('user_project')->insert(array(
				'user_id' => $this->user->id,
			));

		$this->flashMessage('Project saved.');
		$this->redirect('edit', $project->id);
	}

}