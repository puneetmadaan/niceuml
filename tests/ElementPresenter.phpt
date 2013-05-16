<?php

namespace Test;

use Nette,
	Tester,
	Tester\Assert;

$container = require __DIR__ . '/bootstrap.php';
require __DIR__ . '/PresenterTest.php';
Nette\Database\Helpers::loadFromFile($container->{'nette.database.default'}, __DIR__ . '/niceuml_test.sql');


class ElementPresenterTest extends PresenterTest
{

	function testDefault()
	{
		$this->runAction('default', array('projectId' => 1));
	}

	function testEdit()
	{
		$this->runAction('edit', array('id' => 1, 'projectId' => 1));
	}

	function testNew()
	{
		$this->runAction('new', array('projectId' => 1));
	}

}

id(new ElementPresenterTest('Element', $container))->run();

