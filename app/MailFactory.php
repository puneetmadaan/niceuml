<?php

class MailFactory {
	
	protected $mailFactory;
	protected $templateDir;
	protected $defaultFrom;

	
	public function __construct(\Nette\Callback $mailFactory, $templateDir, $defaultFrom) {
		$this->mailFactory = $mailFactory;
		$this->templateDir = $templateDir;
		$this->defaultFrom = $defaultFrom;
	}


	public function create($name, $template, $data = array()) {
		$mail = $this->mailFactory->invoke();
		$template->setFile( $this->templateDir . '/' . $name . '.latte' );
		foreach ($data as $key => $value)
			$template->$key = $value;
		$mail->setHTMLBody($template);
		$mail->setFrom($this->defaultFrom);
		return $mail;
	}

}