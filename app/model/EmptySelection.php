<?php

namespace Model;

use Nette;



/**
 *	Workaround for creating entities (rows need Selection).
 *
 */
class EmptySelection extends Selection {

	public function aggregation($function) {
		return;
	}


	protected function execute() {
		return;
	}


	protected function query($query) {
		return;
	}


	public function create() {
		return $this->rows[] = $this->createRow();
	}

	
	protected function emptyResultSet() {
		return;
	}

	public function accessColumn($key, $selectColumn = TRUE){
		return;
	}

	public function removeAccessColumn($key) {
		return;
	}


	public function insert($data) {
		return FALSE;
	}

	public function update($data) {
		return FALSE;
	}

	public function delete() {
		return FALSE;
	}

}
