<?php

namespace Model;

/*
classes:
  Publication: []
  Book:
    parent: Publication
    attributes:
      +name: string
      -isbn: string
      - ~package
      - '#protected'
    operations:
      +getISBN: string
      +setISBN: Book(isbn: string)
  Author:
    attributes:
      - name
      - birthDate
associations:
  - [Book: *, Author: 1]
*/


class Class extends \Nette\Object {

	protected $name;
	protected $parent;

	protected $attributes = array();
	protected $operations = array();


}

