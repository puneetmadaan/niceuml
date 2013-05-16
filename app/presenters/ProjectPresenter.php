<?php


final class ProjectPresenter extends BasePresenter {

	/** @var Model\Project */
	protected $projectModel;

	/** @var Model\UserDAO */
	protected $userModel;

	/** @var Model\Entity\Project */
	protected $project;


	public function injectModels(Model\ProjectDAO $projectModel, Model\UserDAO $userModel) {
		$this->doInject('projectModel', $projectModel);
		$this->doInject('userModel', $userModel);
	}


	public function startup() {
		parent::startup();
		if (!$this->user->isAllowed('project'))
			$this->forbidden();
	}


	public function renderDefault() {
		$this->template->projects = $this->user->isInRole('admin') ?
			$this->projectModel->table() :
			$this->projectModel->findByUserId($this->user->id);
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


	protected function createComponentProjectForm() {
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

		$project = $this->projectModel->save($this->project, $values);
		if (!$this->project)
			$project->addUser($this->user->id, TRUE);

		$this->flashMessage('Project saved.');
		$this->redirect('edit', $project->id);
	}


	public function handleRemoveUser($user) {
		$this->project->removeUser($user);
		$this->presenter->flashMessage('User removed', 'success');
		$this->redirect('this');
	}


	/**
	 * @return \Nette\Application\UI\Form
	 */
	protected function createComponentAddUserForm() {
		$form = $this->formFactory->create();

		$current = $this->project->related('user_project')->collect('user_id');
		$users = $this->userModel->table();
		if ($current)
			$users->where('id NOT', $current);

		$form->addSelect('user_id', NULL, $users->fetchPairs('id', 'fullName'))
			->setPrompt('Select user')
			->setRequired('Select user');

		$form->addSubmit('send');
		$form->onSuccess[] = callback($this, 'addUserFormSucceeded');

		return $form;
	}


	public function addUserFormSucceeded($form) {
		$this->project->addUser($form['user_id']->value);
		$this->presenter->flashMessage('User added', 'success');
		$this->redirect('this');
	}
}
