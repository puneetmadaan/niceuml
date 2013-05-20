<?php

use Nette\Application\Routers\Route,
	Nette\Config\Compiler,
	Nette\Config\CompilerExtension,
	Nette\Utils\Arrays,
	Nette\Utils\Validators;


/** Config extension for creating routes */
class RoutesExtension extends CompilerExtension
{

	public $routeDefaults = array(
		'mask' => NULL,
		'action' => array(),
		'oneWay' => FALSE,
		'secured' => FALSE,
		'caseSensitive' => FALSE,
	);


	/** @return void */
	public function loadConfiguration()
	{
		$container = $this->getContainerBuilder();
		$config = $this->getConfig();

		$router = $container->addDefinition($this->prefix('router'))
			->setClass('Nette\Application\Routers\RouteList');

		foreach ($config as $key => $value) {
			if ($value instanceof \stdClass) {
				$value = Compiler::filterArguments(array($value));
				$router->addSetup('$route = ?; if ($route instanceof IRouterFactory) $route = $route->createRouter(); $service[] = $route', $value);
			}
			else {
				$value = (array) $value;
				if (!Validators::isNumeric($key))
					array_unshift($value, $key);

				if (!isset($value['mask']) && isset($value[0]))
					$value['mask'] = $value[0];
				elseif (!isset($value['action']) && isset($value[0]))
					$value['action'] = $value[0];
				if (!isset($value['action']) && isset($value[1]))
					$value['action'] = $value[1];

				$value = $value + $this->routeDefaults;

				$flags = 0;
				if ($value['oneWay'] || in_array('oneWay', $value, TRUE))
					$flags |= Route::ONE_WAY;
				if ($value['secured'] || in_array('secured', $value, TRUE))
					$flags |= Route::SECURED;
				if ($value['caseSensitive'] || in_array('caseSensitive', $value, TRUE))
					$flags |= Route::CASE_SENSITIVE;

				$router->addSetup(
					'$service[] = new Nette\Application\Routers\Route(?,?,?)',
					array($value['mask'], $value['action'], $flags)
				);
			}
		}
	}

}
