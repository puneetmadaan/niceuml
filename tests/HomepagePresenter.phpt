<?php

namespace Test;

use Nette,
	Tester,
	Tester\Assert;

$container = require __DIR__ . '/bootstrap.php';
require __DIR__ . '/PresenterTest.php';
Nette\Database\Helpers::loadFromFile($container->{'nette.database.default'}, __DIR__ . '/niceuml_test.sql');


class HomepagePresenterTest extends PresenterTest
{

	function testDefault()
	{
		$this->runAction('default');
	}

}

id(new HomepagePresenterTest('Homepage', $container))->run();

