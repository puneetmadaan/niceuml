<?php



class NestedEntityFactory extends \NiceDAO\EntityFactory {

	public function create($table, array $data = array()) {
		if ($table instanceof \Nette\Database\Table\Selection) {
			$name = $table->getName();
			$class = isset($this->classes[$name]) ? $this->classes[$name] : $this->defaultClass;
			if (is_object($class))
				return $class->create($table, $data);
		}
		return parent::create($table, $data);
	}


}
