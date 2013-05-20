<?php


/**
 * Homepage presenter.
 */
final class HomepagePresenter extends BasePresenter
{

	/** @var Model\ProjectDAO */
	protected $projectModel;


	/** @return void */
	public function startup()
	{
		parent::startup();
		if (!$this->user->isAllowed('usage'))
			$this->forbidden();
	}


	/** @return void */
	public function injectProjectModel(Model\ProjectDAO $projectModel)
	{
		$this->doInject('projectModel', $projectModel);
	}


	/** @return void */
	public function renderDefault()
	{
		$projects = $this->projectModel->findByUserId($this->user->id);
		$this->template->projects = $projects;
	}

}
