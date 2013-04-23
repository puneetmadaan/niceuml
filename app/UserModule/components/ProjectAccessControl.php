<?php

namespace UserModule;

use BaseControl,
	IFormFactory,
	Model\Entity\Project;


class ProjectAccessControl extends BaseControl {

	protected $project;
	protected $formFactory;
	protected $model;

	public function __construct(Project $project, IFormFactory $formFactory, Model $model) {
		$this->project = $project;
		$this->formFactory = $formFactory;
		$this->model = $model;
	}


	public function handleDelete($user) {
		$row = $this->project->related('user_project')->where('user_id', $user)->fetch();
		if (!$row || $row->role === 'owner')
			$this->error();
		$row->delete();

		$this->presenter->flashMessage('User removed', 'success');
		$this->redirect('this');
	}


	public function render() {
		$this->template->project = $this->project;
		parent::render();
	}


	/**
	 * @return \Nette\Application\UI\Form
	 */
	protected function createComponentAddUserForm() {
		$form = $this->formFactory->create();

		$current = $this->project->related('user_project')->collect('user_id');
		$users = $this->model->table();
		if ($current)
			$users->where('id NOT', $current);

		$form->addSelect('user_id', NULL, $users->fetchPairs('id', 'fullName'))
			->setPrompt('Select user')
			->setRequired('Select user');

		$form->addCheckbox('admin', 'Admin');

		$form->addSubmit('send');
		$form->onSuccess[] = callback($this, 'addUserFormSucceeded');

		return $form;
	}

	public function addUserFormSucceeded($form)
	{
		$values = $form->getValues();

		$this->project->related('user_project')->insert(array(
			'user_id' => $values->user_id,
			'role' => $values->admin ? 'admin' : 'user',
		));

		$this->presenter->flashMessage('User added', 'success');
		$this->redirect('this');
	}


}
