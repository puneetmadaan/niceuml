<?php

namespace Model\Entity;


class Relation extends Base {

	/** @return Element */
	public function getOtherEnd(Element $start) {
		if ($start->id === $this->start_id)
			return $this->end;
		if ($start->id === $this->end_id)
			return $this->start;
	}

}
