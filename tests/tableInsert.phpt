<?php

namespace Test;

use Nette,
	Tester,
	Tester\Assert;

$container = require __DIR__ . '/bootstrap.php';

$db = $container->nette->database->default;

Nette\Database\Helpers::loadFromFile($db, __DIR__ . '/mysql-nette_test1.sql');

$queries = array();

$db->onQuery[] = function ($result, $params) use (&$queries) {
	$queries[] = array( $result->queryString, $params );
};

$table = $db->table('book');

Assert::same('Geek', $table->insert(array( 'author_id' => 13 ))->author->name);
Assert::same('Jakub Vrana', $table->insert(array( 'author_id' => 11 ))->author->name);
Assert::same(4, count($queries));