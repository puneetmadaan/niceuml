<?php

namespace UserModule;

use Nette\Application\Routers\RouteList,
	Nette\Application\Routers\Route;


class RouterFactory extends \Nette\Object implements \IRouterFactory {


	/**
	 * @return Nette\Application\IRouter
	 */
	public function createRouter()
	{
		$router = new RouteList('User');
		$router[] = new Route('users/<presenter>/<action>[/<id>]', 'Homepage:default');
		return $router;
	}


}