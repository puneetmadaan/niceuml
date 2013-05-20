<?php

namespace Model;

use Nette\Config\Compiler,
	Nette\Config\CompilerExtension;


/** Config extension for adding entity types */
class EntityExtension extends CompilerExtension
{

	const DEFAULT_KEY = 'default';


	/** @return void */
	public function loadConfiguration()
	{
		$container = $this->getContainerBuilder();
		$config = $this->getConfig();

		$params = array('array data', 'Nette\Database\Table\Selection table');
		$args = array('%data%', '%table%');

		$factories = array();
		foreach ($config as $key => $value) {
			$factory = $container->addDefinition($this->prefix($key));
			Compiler::parseService($factory, $value);

			$factoryArgs = $factory->factory ? $factory->factory->arguments : array();
			$factory->setParameters(array_merge($params, $factory->parameters))
				->setArguments(array_merge($args, $factoryArgs));
			$factories[$key] = $this->prefix($key);
		}
		$default = isset($factories[self::DEFAULT_KEY]) ? $factories[self::DEFAULT_KEY] : NULL;
		$container->addDefinition('model.entityFactory')
			->setClass('Model\Database\DIEntityFactory', array($factories, $default) );
	}
}
