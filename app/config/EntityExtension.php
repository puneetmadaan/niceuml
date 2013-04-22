<?php

use Nette\Config\CompilerExtension;


class EntityExtension extends CompilerExtension {

	public function loadConfiguration() {
		$container = $this->getContainerBuilder();
		$config = $this->getConfig();
		foreach ($config as $key => $value) {
			$router = $container->addDefinition($this->prefix($key))
				->setShared(FALSE)
				->setParameters(array('array data', 'Nette\Database\Table\Selection table'))
				->setClass($value, array('%data%', '%table%'));
		}
	}
}
