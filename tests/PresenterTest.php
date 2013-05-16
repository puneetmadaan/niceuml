<?php

namespace Test;

use Nette,
	Tester,
	Tester\Assert;


class PresenterTest extends Tester\TestCase
{

	protected $name;
	protected $presenterFactory;
	protected $presenter;
	protected $response;
	protected $html;


	public function __construct($name, Nette\DI\IContainer $container)
	{
		$this->name = $name;
		$this->presenterFactory = $container->getByType('Nette\Application\IPresenterFactory');
	}


	protected function setUp()
	{
		$this->presenter = $this->presenterFactory->createPresenter($this->name);
		$this->presenter->autoCanonicalize = FALSE;
		$this->presenter->user->login(new Nette\Security\Identity(
			1,
			'admin',
			array(
				'id' => 1,
				'name' => 'Admin',
				'surname' => 'Administrator',
				'email' => '',
				'role' => 'admin',
				'login' => 'administrator',
			)
		));
	}


	protected function runAction($name, $args = array())
	{
		$request = new Nette\Application\Request($this->name, 'GET', array('action' => $name) + $args);
		$this->response = $this->presenter->run($request);
		Assert::true( $this->response instanceof Nette\Application\Responses\TextResponse );
		Assert::true( $this->response->getSource() instanceof Nette\Templating\ITemplate );
		ob_start();
		$this->response->getSource()->render();
		$this->html = ob_get_clean();
	}


	protected function tearDown()
	{
		$this->html = $this->response = $this->preseneter = NULL;
	}

}

