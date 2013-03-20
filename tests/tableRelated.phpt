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

$table = $db->table('author');

foreach ($table as $a) {
	foreach( $a->related('book') as $book ) {
		foreach ($book->related('book_tag') as $t) {
			$t->tag;
		}
	}
}

Assert::same(4, count($queries));