<?php

namespace ClassModule\Model\Entity;


class Operation extends \Model\Entity\Base {


	public function toUML() {
		$res = '';
		switch ($this->visibility) {
			case 'public': $res.='+'; break;
			case 'private': $res.='-'; break;
			case 'protected': $res.='#'; break;
			case 'package': $res.='~'; break;
			default: /** no sign */
		}
		if ($this->abstract) $res.='abstract ';
		if ($this->static) $res.='static ';
		$res.=$this->name;
		$params = array();
		foreach ($this->related('class_operationParameter') as $p){
			$param = '';
			$param .= ($p->direction && $p->direction !== 'in') ? ($p->direction . ' ') : '';
			$param .= $p->name;
			if ($p->type_id !== NULL)
				$param.=':'.$p->ref('class_class', 'type_id')->name;
			elseif ($p->type !== NULL)
				$param.=':'.$p->type;
			if ($p->multiplicity)
				$param.='['.$p->multiplicity.']';
			if ($p->defaultValue)
				$param.='='.$p->defaultValue;
			$params[] = $param;
		}
		$res.='('.implode(', ', $params).')';
		if ($this->returnType_id !== NULL)
			$res.=':'.$this->ref('class_class', 'returnType_id')->name;
		elseif ($this->returnType !== NULL)
			$res.=':'.$this->returnType;
		return $res;
	}

}
