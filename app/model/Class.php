<?php

namespace Model;

/**
 * classes:
 *   Publication: []
 *   Book:
 *     parent: Publication
 *     attributes:
 *       - name(public, string)
 *       - isbn(protected, string)
 *     operations:
 *       - getISBN(public, string)
 *       - setISBN(public, string, [isbn: string])
 *   Author:
 *     attributes:
 *       - name()
 * associations:
 *   - [Book: *, Author: *]
*/


class Class extends \Nette\Object {

	protected $name;
	protected $parent;

	protected $attributes = array();
	protected $operations = array();


}

