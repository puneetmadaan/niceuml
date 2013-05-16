<?php

namespace Model\Entity;

use Nette;


class Relation extends BaseEntity
{

	/** @return Relation self */
	public function setStart(Element $start)
	{
		$this->setColumn('start_id', (int) $start->id);
		return $this;
	}


	/** @return Relation self */
	public function setEnd(Element $end)
	{
		$this->setColumn('end_id', (int) $end->id);
		return $this;
	}


	/** @return Element */
	public function getOtherEnd(Element $start)
	{
		if ($start->id === $this->start_id)
			return $this->end;
		if ($start->id === $this->end_id)
			return $this->start;
	}

}
