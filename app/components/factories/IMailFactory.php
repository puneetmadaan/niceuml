<?php


interface IMailFactory {

	/** @return Nette\Mail\Message */
	public function create($name, $template, $data = array());

}
