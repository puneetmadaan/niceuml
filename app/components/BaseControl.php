<?php


/** Parent class for all controls */
class BaseControl extends Nette\Application\UI\Control
{


	/**
	 * Derives template path from class name
	 * @return string
	 */
	protected function getTemplateFilePath()
	{
		$reflection = $this->getReflection();
		$dir = dirname($reflection->getFileName());
		$filename = $reflection->getShortName() . ".latte";

		return $dir . DIRECTORY_SEPARATOR . $filename;
	}


	/** @return void */
	public function render()
	{
		$this->template->setFile($this->getTemplateFilePath());
		$this->template->render();
	}

}
