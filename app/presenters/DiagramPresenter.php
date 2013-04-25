<?php


final class DiagramPresenter extends ModellingPresenter {

	/** @var Model\Diagram */
	protected $diagramModel;

	/** @var INewDiagramControlFactory */
	protected $newDiagramControlFactory;

	/** @var IDiagramControlFactory */
	protected $diagramControlFactory;

	/** @var Model\Entity\Diagram */
	protected $diagram;


	public function injectModel(Model\Diagram $diagramModel) {
		$this->doInject('diagramModel', $diagramModel);
	}


	public function injectDiagramControlFactories(INewDiagramControlFactory $new, IDiagramControlFactory $edit) {
		$this->doInject('newDiagramControlFactory', $new);
		$this->doInject('diagramControlFactory', $edit);
	}

	public function actionDefault() {
	}


	public function renderDefault() {
		$this->template->diagrams = $this->diagramModel->table()->where('project_id', $this->project->id);
	}


	public function actionEdit($id, $relation = NULL) {
		$this->diagram = $this->checkDiagram($id);
		if ($relation !== NULL)
			$this->relation = $this->checkRelation($relation);
	}


	public function renderEdit() {
		$this->template->diagram = $this->diagram;
	}


	public function handleDelete($diagram = NULL) {
		if (empty($this->diagram) === empty($diagram))
			$this->error();
		$diagram = $this->diagram ?: $this->checkDiagram($diagram);

		if (!$this->user->isAllowed($diagram, 'delete'))
			$this->forbidden();
		$diagram->delete();
		$this->flashMessage('Diagram was deleted.');
		$this->redirect('default');
	}


	protected function createComponentNewDiagramControl() {
		return $this->newDiagramControlFactory->create($this->project);
	}


	protected function createComponentDiagramControl() {
		if (!$this->diagram)
			$this->error();
		return $this->diagramControlFactory->create($this->diagram);
	}


	protected function checkDiagram($id) {
		if ($id === NULL)
			$this->error();
		$diagram = $this->diagramModel->get((int) $id);
		if (!$diagram || $diagram->project_id !== $this->project->id)
			$this->error();
		if (!$this->user->isAllowed($diagram))
			$this->forbidden();
		return $diagram;
	}

}
