<?php


/** Creates (from DI container) and configures mail message with template */
class MailFactory
{

	protected $container;
	protected $templateDir;
	protected $defaultFrom;

	/**
	 * @param string directory with mail templates
	 * @param string default e-mail sender
	 * @param Nette\DI\Container
	 */
	public function __construct($templateDir, $defaultFrom, Nette\DI\Container $container)
	{
		$this->templateDir = $templateDir;
		$this->defaultFrom = $defaultFrom;
		$this->container = $container;
	}


	/**
	 * @param  template name
	 * @param  template object
	 * @param  array e-mail data
	 * @return Nette\Mail\Message
	 */
	public function create($name, $template, $data = array())
	{
		$mail = $this->container->createNette__mail();
		$template->setFile( $this->templateDir . DIRECTORY_SEPARATOR . $name . '.latte' );
		foreach ($data as $key => $value)
			$template->$key = $value;
		$mail->setHTMLBody($template);
		$mail->setFrom($this->defaultFrom);
		return $mail;
	}

}
