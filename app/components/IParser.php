<?php


interface IParser {

	/** @param string */
	/** @return Model\Command|NULL */
	public function parse($input);


	/** @param Model\Command */
	/** @return string */
	public function build(Model\Command $command);

}
