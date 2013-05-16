<?php



class DiagramControl extends BaseControl {

	/** @persistent */
	public $placement;

	/** @var Model\BaseDAO */
	protected $diagramModel;

	/** @var Model\BaseDAO */
	protected $placementModel;

	/** @var IFormFactory */
	protected $formFactory;

	/** @var Model\Entity\Diagram */
	protected $diagram;

	protected $placementRow;


	public function __construct(
		Model\Entity\Diagram $diagram, Model\BaseDAO $diagramModel, Model\BaseDAO $placementModel,
		IFormFactory $formFactory
	) {
		$this->diagram = $diagram;
		$this->diagramModel = $diagramModel;
		$this->placementModel = $placementModel;
		$this->formFactory = $formFactory;
	}


	public function handleDeletePlacement() {
		$placement = $this->checkPlacement($this->placement);
		$placement->delete();
		$this->presenter->flashMessage('Placement was deleted.');
		$this->redirect('this', array('placement' => NULL));
	}


	public function render() {
		$this->template->diagram = $this->diagram;
		$this->template->placement = (bool) $this->placement;
		$this->template->placements = $this->placementModel->table()
			->where('diagram_id', $this->diagram->id);
		parent::render();
	}


	protected function createComponentForm() {
		$form = $this->formFactory->create();

		$form->addText('name', 'Name')
			->setDefaultValue($this->diagram->name);
		$form->addSubmit('send', 'Save');
		$form->onSuccess[] = $this->formSucceeded;
		return $form;
	}


	public function formSucceeded($form) {
		$this->diagramModel->save($this->diagram, $form->values);
		$this->presenter->flashMessage('Data saved.');
		$this->redirect('this');
	}


	protected function createComponentNewPlacementControl() {
		$form = $this->formFactory->create();

		$elements = $this->diagram->project->related('element');
		$placed = $this->placementModel->table()->where('diagram_id', $this->diagram->id)
			->collect('element_id');
		if ($placed)
			$elements->where('id NOT', $placed);
		$form->addSelect('element_id', 'Element', $elements->fetchPairs('id', 'caption'))
			->setPrompt('Choose')->setRequired();

		$form->addText('posX', 'X')
			->setRequired()
			->addRule($form::INTEGER);
		$form->addText('posY', 'Y')
			->setRequired()
			->addRule($form::INTEGER);
		$form->addSubmit('send', 'Save');
		$form->onSuccess[] = $this->newPlacementSucceeded;
		return $form;
	}


	public function newPlacementSucceeded($form) {
		$values = $form->values;
		$values->diagram_id = $this->diagram->id;
		$this->placementModel->save(NULL, $values);
		$this->redirect('this');
	}


	protected function createComponentPlacementControl() {
		$this->placementRow = $this->checkPlacement($this->placement);
		$form = $this->formFactory->create();
		$form->addText('posX', 'X')
			->setRequired()
			->addRule($form::INTEGER);
		$form->addText('posY', 'Y')
			->setRequired()
			->addRule($form::INTEGER);
		$form->addSubmit('send', 'Save');
		$form->defaults = $this->placementRow;
		$form->onSuccess[] = $this->placementSucceeded;
		return $form;
	}


	public function placementSucceeded($form) {
		$values = $form->values;
		$this->placementModel->save($this->placementRow, $values);
		$this->redirect('this', array('placement' => NULL));
	}


	protected function checkPlacement($id) {
		if ($id === NULL)
			$this->presenter->error();
		$placement = $this->placementModel->get(array($this->diagram->id, (int) $id));
		if (!$placement)
			$this->presenter->error();
		return $placement;
	}


	public function renderScripts() {
	}

}
