<?php

namespace Test;

use Nette,
	Tester,
	Tester\Assert;

$container = require __DIR__ . '/bootstrap.php';
require __DIR__ . '/PresenterTest.php';
Nette\Database\Helpers::loadFromFile($container->{'nette.database.default'}, __DIR__ . '/niceuml_test.sql');

class DiagramPresenterTest extends PresenterTest
{

	function testDefault()
	{
		$this->runAction('default', array('projectId' => 1));
	}

	function testEdit()
	{
		$this->runAction('edit', array('projectId' => 1, 'id' => 1));
	}

	function testNew()
	{
		$this->runAction('new', array('projectId' => 1));
	}

}

id(new DiagramPresenterTest('Diagram', $container))->run();

