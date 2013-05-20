<?php

namespace ClassModule;

use IDiagramControl,
	BaseControl,
	Model\ElementDAO,
	Model\RelationDAO,
	Model\Entity\Diagram;


/**
 * Class diagram renderer
 */
class ClassDiagramControl extends BaseControl implements IDiagramControl
{

	/** @var Diagram */
	protected $diagram;
	/** @var ElementDAO */
	protected $elementModel;
	/** @var RelationDAO */
	protected $relationModel;
	/** @var bool */
	protected $rendered = FALSE;


	public function __construct(ElementDAO $elementModel, RelationDAO $relationModel)
	{
		$this->elementModel = $elementModel;
		$this->relationModel = $relationModel;
	}


	/** @return void */
	public function setDiagram(Diagram $diagram)
	{
		$this->diagram = $diagram;
	}


	/** @return void */
	protected function beforeRender()
	{
		if ($this->rendered) return;
		$this->rendered = TRUE;

		$this->template->diagram = $this->diagram;
		$this->template->placements = $placements = $this->diagram->related('placement');
		$ids = $placements->collect('element_id');
		if (count($ids)) {
			$this->template->elements = $this->elementModel->table()->where('id', $ids);
			$this->template->relations = $this->relationModel->table()->where(array(
				'start_id' => $ids,
				'end_id' => $ids,
			));
		}
		else {
			$this->template->elements = $this->template->relations = array();
		}
	}


	/** @return void */
	public function render()
	{
		$this->beforeRender();
		$this->template->mode = NULL;
		parent::render();
	}


	/** @return void */
	public function renderScripts()
	{
		$this->beforeRender();
		$this->template->mode = 'scripts';
		parent::render();
	}
}
