<?php

use Nette\Config\Compiler,
	Nette\Config\CompilerExtension;


class EntityExtension extends CompilerExtension {

	public function loadConfiguration() {
		$container = $this->getContainerBuilder();
		$config = $this->getConfig();

		$params = array('array data', 'Nette\Database\Table\Selection table');
		$args = array('%data%', '%table%');

		foreach ($config as $key => $value) {
			$factory = $container->addDefinition($this->prefix($key));
			Compiler::parseService($factory, $value);

			$factory->setParameters(array_merge($params, $factory->parameters))
				->setArguments(array_merge($args, $factory->factory->arguments));
		}
	}
}
