<?php


final class ProjectPresenter extends BasePresenter {

	/** @var Model\Project */
	protected $projectModel;

	/** @var IProjectAccessControlFactory */
	protected $projectAccessControlFactory;

	/** @var Model\Entity\Project */
	protected $project;


	public function injectProjectModel(Model\Project $projectModel) {
		$this->doInject('projectModel', $projectModel);
	}


	public function injectAccessControlFactory(IProjectAccessControlFactory $factory = NULL) {
		if ($factory !== NULL)
			$this->doInject('projectAccessControlFactory', $factory);
	}


	public function startup() {
		parent::startup();
		if (!$this->user->isAllowed('project'))
			$this->forbidden();
	}


	public function renderDefault() {
		$this->template->projects = $this->projectModel->table()
			->where('user_project:user_id = ?', $this->user->id);
	}


	public function actionEdit($id) {
		$project = $this->projectModel->get($id);

		if (!$project)
			$this->error();
		if (!$this->user->isAllowed($project, 'edit'))
			$this->forbidden();

		$this->project = $project;
	}


	public function actionNew() {
		$this->view = 'edit';
	}


	public function renderEdit() {
		$this->template->new = $this->project === NULL;
		$this->template->project = $this->project;
		$this->template->isAccessControl = $this->projectAccessControlFactory !== NULL;
	}


	public function handleDelete() {
		if (!$this->project)
			$this->error();
		if (!$this->user->isAllowed($this->project, 'delete'))
			$this->forbidden();
		$this->project->delete();
		$this->flashMessage('Project was deleted.');
		$this->redirect('default');
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

		$project = $this->projects->save($this->project, $values);

		if (!$this->project)
			$project->related('user_project')->insert(array(
				'user_id' => $this->user->id,
				'role' => 'owner',
			));

		$this->flashMessage('Project saved.');
		$this->redirect('edit', $project->id);
	}


	public function createComponentProjectAccessControl() {
		if ($this->projectAccessControlFactory === NULL)
			throw new Nette\InvalidArgumentException("No factory for component 'accessControl' has been set.");
		return $this->projectAccessControlFactory->create($this->project);
	}

}
