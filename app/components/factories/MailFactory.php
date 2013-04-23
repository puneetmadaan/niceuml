<?php


class MailFactory implements IMailFactory {

	protected $container;
	protected $templateDir;
	protected $defaultFrom;


	public function __construct(Nette\DI\Container $container, $templateDir, $defaultFrom) {
		$this->container = $container;
		$this->templateDir = $templateDir;
		$this->defaultFrom = $defaultFrom;
	}


	public function create($name, $template, $data = array()) {
		$mail = $this->container->createNette__mail();
		$template->setFile( $this->templateDir . DIRECTORY_SEPARATOR . $name . '.latte' );
		foreach ($data as $key => $value)
			$template->$key = $value;
		$mail->setHTMLBody($template);
		$mail->setFrom($this->defaultFrom);
		return $mail;
	}

}
