<?php

namespace ClassModule;

use FormFactory,
	RelationControl,
	Model\ElementDAO,
	Model\RelationType,
	Model\Entity\Relation,
	ClassModule\Model\AssociationDAO;


class AssociationControl extends RelationControl {

	public function __construct(AssociationDAO $model, RelationType $types, ElementDAO $elementModel, FormFactory $formFactory)
	{
		parent::__construct($model, $types, $elementModel, $formFactory);
	}


	public function setRelation(Relation $relation)
	{
		$this->relation = $this->model->getByParent($relation);
	}


	protected function addFormControls($form) {
		$directions = array('none' => 'None', 'uni' => 'Unidirectional', 'bi' => 'Bidirectional');
		$form->addSelect('direction', 'Direction', $directions);
		$form->addText('sourceRole', 'Source role', NULL, 50);
		$form->addText('sourceMultiplicity', 'Source multiplicity', NULL, 10);
		$form->addText('targetRole', 'Target role', NULL, 50);
		$form->addText('targetMultiplicity', 'Target multiplicity', NULL, 10);
		if ($this->relation)
			$form->setDefaults($this->relation->parent);
	}


}
