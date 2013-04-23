<?php


abstract class ModellingPresenter extends BasePresenter {

	/** @persistent */
	public $projectId;

	/** @var Model\Entity\Project */
	protected $project;

	/** @var Model\Project */
	protected $projectModel;

	/** @var IProjectTreeControlFactory */
	protected $projectTreeControlFactory;

	/** @var ISourceControlFactory */
	protected $sourceControlFactory;

	/** @var IConsoleControlFactory */
	protected $consoleControlFactory;


	public function injectProjectModel(Model\Project $projectModel) {
		$this->doInject('projectModel', $projectModel);
	}


	public function injectModellingFactories(IProjectTreeControlFactory $tree, ISourceControlFactory $source, IConsoleControlFactory $console) {
		$this->doInject('projectTreeControlFactory', $tree);
		$this->doInject('sourceControlFactory', $source);
		$this->doInject('consoleControlFactory', $console);
	}


	protected function startup() {
		parent::startup();
		$this->project = $this->checkProject($this->projectId);
	}


	protected function beforeRender() {
		parent::beforeRender();
		$this->template->project = $this->project;
	}


	protected function createComponentProjectTreeControl() {
		return $this->projectTreeControlFactory->create($this->project);
	}


	protected function createComponentSourceControl() {
		return $this->sourceControlFactory->create($this->project);
	}


	protected function createComponentConsole() {
		return $this->consoleControlFactory->create($this->project);
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
