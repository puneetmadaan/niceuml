<?php


/** Router factory */
interface IRouterFactory
{

	/** @return Nette\Application\IRouter */
	public function createRouter();

}
