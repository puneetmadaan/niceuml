<?php

use Nette\Application\Routers\RouteList,
	Nette\Application\Routers\Route;


/**
 * Router factory.
 */
class RouterListFactory extends Nette\Object implements IRouterFactory {

	protected $routes = array();

	/**
	 * @param Nette\Application\IRouter|IRouterFactory route to add
	 * @return RouterListFactory provides a fluent interface
	 */
	public function add($route) {
		if ( !($route instanceof Nette\Application\IRouter || $route instanceof IRouterFactory) ) {
			throw new Nette\InvalidArgumentException("Argument must be IRouter of IRouterFactory instance.");
		}
		$this->routes[] = $route;
		return $this;
	}


	/**
	 * @return Nette\Application\IRouter
	 */
	public function createRouter()
	{
		$router = new RouteList();
		$router[] = new Route('index.php', 'Homepage:default', Route::ONE_WAY);

		foreach ($this->routes as $route) {
			$router[] = ($route instanceof IRouterFactory) ? $route->createRouter() : $route;
		}

		$router[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');
		return $router;
	}

}
