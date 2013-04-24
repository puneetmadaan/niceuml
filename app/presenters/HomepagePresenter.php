<?php


/**
 * Homepage presenter.
 */
final class HomepagePresenter extends BasePresenter {

	protected $projectModel;


	public function injectProjectModel(Model\Project $projectModel) {
		$this->doInject('projectModel', $projectModel);
	}


	public function renderDefault() {
		$projects = $this->projectModel->table();
		$this->projectModel->filterAllowed($this->user, $projects);
		$this->template->projects = $projects;
	}

}
