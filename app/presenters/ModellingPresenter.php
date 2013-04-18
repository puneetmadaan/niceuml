<?php



abstract class ModellingPresenter extends BasePresenter {

	/** @persistent */
	public $projectId;

	protected $project;

	/** @var Model\Project */
	protected $projectModel;


	public function injectProjectModel(Model\Project $projectModel) {
		$this->doInject('projectModel', $projectModel);
	}

	protected function startup() {
		parent::startup();
		$this->project = $this->checkProject($this->projectId);
	}

	protected function beforeRender() {
		parent::beforeRender();
		$this->template->project = $this->project;
	}

	public function createComponentProjectTreeControl() {
		return $this->context->createProjectTreeControl($this->project);
	}

	protected function checkProject($id) {
		if ($id === NULL)
			$this->redirect(':Homepage:');
		$project = $this->projectModel->get((int) $id);
		if (!$project)
			$this->error();
		if (!$this->user->isAllowed($project))
			$this->forbidden();
		return $project;
	}

}
