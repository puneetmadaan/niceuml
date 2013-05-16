<?php

namespace Test;

use Nette,
	Tester,
	Tester\Assert;

$container = require __DIR__ . '/bootstrap.php';
require __DIR__ . '/PresenterTest.php';
Nette\Database\Helpers::loadFromFile($container->{'nette.database.default'}, __DIR__ . '/niceuml_test.sql');

class ProjectPresenterTest extends PresenterTest
{

	function testDefault()
	{
		$this->runAction('default');
	}

	function testEdit()
	{
		$this->runAction('edit', array('id' => 1));
	}

	function testNew()
	{
		$this->runAction('new');
	}

}

id(new ProjectPresenterTest('Project', $container))->run();

