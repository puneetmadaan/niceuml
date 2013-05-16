<?php


/**
 * Homepage presenter.
 */
final class HomepagePresenter extends BasePresenter {

	protected $projectModel;

	public function startup() {
		parent::startup();
		if (!$this->user->isAllowed('usage'))
			$this->forbidden();
	}

	public function injectProjectModel(Model\ProjectDAO $projectModel) {
		$this->doInject('projectModel', $projectModel);
	}


	public function renderDefault() {
		$projects = $this->projectModel->findByUserId($this->user->id);
		$this->template->projects = $projects;
	}

}
