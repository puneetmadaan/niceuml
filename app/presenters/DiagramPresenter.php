<?php


/** Presenter for creating, editting and rendering diagrams */
final class DiagramPresenter extends ModellingPresenter
{

	/** @var Model\DiagramDAO */
	protected $model;

	/** @var Model\ElementDAO */
	protected $elementModel;

	/** @var Model\PlacementDAO */
	protected $placementModel;

	/** @var Model\DiagramType */
	protected $types;

	/** @var DiagramControlFactory */
	protected $controlFactory;

	/** @var Model\Entity\Diagram */
	protected $diagram;

	/** @var Model\Entity\Placement */
	protected $placement;


	/** @return void */
	public function inject(Model\DiagramDAO $model, Model\DiagramType $types, DiagramControlFactory $ctrlFactory)
	{
		$this->doInject('model', $model);
		$this->doInject('types', $types);
		$this->doInject('controlFactory', $ctrlFactory);
	}


	/** @return void */
	public function injectElements(Model\ElementDAO $elementModel, Model\PlacementDAO $placementModel)
	{
		$this->doInject('elementModel', $elementModel);
		$this->doInject('placementModel', $placementModel);
	}


	/** @return void */
	public function actionDefault()
	{
	}


	/** @return void */
	public function renderDefault()
	{
		$this->template->diagrams = $this->model->table()->where('project_id', $this->project->id);
	}


	/** @return void */
	public function actionEdit($id, $placement = NULL)
	{
		$this->diagram = $this->checkDiagram($id);
		if ($placement !== NULL)
			$this->placement = $this->checkPlacement($placement);
	}


	/** @return void */
	public function renderEdit()
	{
		$this->template->diagram = $this->diagram;
		$this->template->renderDiagram = $this->controlFactory->has($this->diagram->type);
		$this->template->placements = $this->placementModel->table()->where('diagram_id', $this->diagram->id);
	}


	/** @return void */
	public function handleDelete($diagram = NULL)
	{
		if (empty($this->diagram) === empty($diagram))
			$this->error();
		$diagram = $this->diagram ?: $this->checkDiagram($diagram);

		if (!$this->user->isAllowed($diagram, 'delete'))
			$this->forbidden();
		$diagram->delete();
		$this->flashMessage('Diagram was deleted.');
		$this->redirect('default');
	}


	/** @return void */
	public function handleDeletePlacement()
	{
		if (empty($this->placement))
			$this->error();
		$this->placement->delete();
		$this->presenter->flashMessage('Placement was deleted.');
		$this->redirect('this', array('placement' => NULL));
	}


	/** @return Nette\Application\UI\Form */
	protected function createComponentForm()
	{
		$form = $this->formFactory->create();

		if (!$this->diagram)
			$form->addSelect('type', 'Type', $this->types->getLabels())
				->setPrompt('Choose a type')
				->setRequired('Choose a type');

		$form->addText('name', 'Name')
			->setRequired('Enter diagram name.')
			->addRule($this->checkUniqueName, 'Name already in use.');

		$form->addSubmit('send', 'Save');

		$form->onSuccess[] = $this->formSucceeded;
		if ($this->diagram)
			$form->setDefaults($this->diagram);
		return $form;
	}


	/** @return bool */
	public function checkUniqueName($input)
	{
		$table = $this->model->findByProject($this->project)->where('name', $input->value);
		if ($this->diagram)
			$table->where('id != ?', $this->diagram->id);
		return $table->fetch() ? FALSE : TRUE;
	}


	/** @return void */
	public function formSucceeded($form)
	{
		$values = $form->values;
		if (!$this->diagram)
			$values->project = $this->project;
		$diagram = $this->model->save($this->diagram, $values);
		$this->presenter->flashMessage('Data saved.');
		$this->presenter->redirect('edit', $diagram->id);
	}


	/** @return Nette\Application\UI\Form */
	protected function createComponentPlacementForm()
	{
		if (!$this->diagram)
			$this->error();

		$form = $this->formFactory->create();

		if (!$this->placement) {
			$types = $this->types->getElementTypes($this->diagram->type);
			$elements = $this->elementModel->findByProject($this->project, $types);

			$placed = $this->placementModel->table()->where('diagram_id', $this->diagram->id)
				->collect('element_id');
			if ($placed)
				$elements->where('id NOT', $placed);
			$form->addSelect('element_id', 'Element', $elements->fetchPairs('id', 'caption'))
				->setPrompt('Choose')
				->setRequired('Choose');
		}

		$form->addText('posX', 'X')
			->setRequired()
			->addRule($form::INTEGER);
		$form->addText('posY', 'Y')
			->setRequired()
			->addRule($form::INTEGER);

		$form->addSubmit('send', 'Save');
		if ($this->placement)
			$form->defaults = $this->placement;
		$form->onSuccess[] = $this->placementSucceeded;
		return $form;
	}


	/** @return void */
	public function placementSucceeded($form)
	{
		$values = $form->values;
		if (!$this->placement)
			$values->diagram_id = $this->diagram->id;
		$this->placementModel->save($this->placement, $values);
		$this->flashMessage('Data saved.');
		$this->redirect('this', array('placement' => NULL));
	}


	/** @return IDiagramControl */
	protected function createComponentDiagramControl()
	{
		if (!$this->diagram)
			$this->error();
		$control = $this->controlFactory->create($this->diagram->type);
		if (!$control)
			$this->error();
		$control->setDiagram($this->diagram);
		return $control;
	}


	/** @return Model\Entity\Diagram */
	protected function checkDiagram($id)
	{
		if ($id === NULL)
			$this->error();
		$diagram = $this->model->get((int) $id);
		if (!$diagram || $diagram->project_id !== $this->project->id)
			$this->error();
		if (!$this->user->isAllowed($diagram, 'edit'))
			$this->forbidden();
		return $diagram;
	}


	/** @return Model\Entity\Placement */
	protected function checkPlacement($id)
	{
		if (!$this->diagram || $id === NULL)
			$this->error();
		$placement = $this->placementModel->get(array($this->diagram->id, (int) $id));
		if (!$placement) // diagram source deletes them
			$this->redirect('this', array('placement' => NULL));
		return $placement;
	}

}
