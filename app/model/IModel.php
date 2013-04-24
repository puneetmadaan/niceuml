<?php

namespace Model;


interface IModel {

	function create($data = NULL);

	function get($id);

	function save($entity = NULL, $data = NULL);

	function delete($entity);


}
