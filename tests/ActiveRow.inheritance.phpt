<?php

namespace Test;

use Nette,
	Tester,
	Tester\Assert;

$container = require __DIR__ . '/bootstrap.php';
$db = $container->database;
Nette\Database\Helpers::loadFromFile($db, __DIR__ . '/niceuml_test.sql');

$row = $db->table('core_note')->fetch();

Assert::same('Nette\Database\Table\ActiveRow', get_class($row));
Assert::same('Nette\Database\Table\ActiveRow', get_class($row->ref('id')));



