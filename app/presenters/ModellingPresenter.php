<?php


abstract class ModellingPresenter extends BasePresenter {

	/** @persistent */
	public $projectId;

	/** @var Model\Entity\Project */
	protected $project;

	/** @var Model\Project */
	protected $projectModel;

	/** @var ProjectTreeControlFactory */
	protected $projectTreeControlFactory;

	/** @var SourceControlFactory */
	protected $sourceControlFactory;

	/** @var ConsoleControlFactory */
	protected $consoleControlFactory;


	public function injectProjectModel(Model\ProjectDAO $projectModel) {
		$this->doInject('projectModel', $projectModel);
	}


	public function injectModellingFactories(ProjectTreeControlFactory $tree, SourceControlFactory $source, ConsoleControlFactory $console) {
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
		$this->layout = 'modelling';
		$this->template->project = $this->project;
	}


	protected function createComponentProjectTreeControl() {
		$control = $this->projectTreeControlFactory->create();
		$control->setProject($this->project);
		return $control;
	}


	protected function createComponentSourceControl() {
		$control = $this->sourceControlFactory->create();
		$control->setProject($this->project);
		return $control;
	}


	protected function createComponentConsoleControl() {
		$control = $this->consoleControlFactory->create();
		$control->setProject($this->project);
		return $control;
	}


	protected function checkProject($id) {
		if ($id === NULL)
			$this->redirect(':Homepage:');
		$project = $this->projectModel->get((int) $id);
		if (!$project)
			$this->error();
		if (!$this->user->isAllowed($project, 'view'))
			$this->forbidden();
		return $project;
	}

}
