<?php


class ConsoleControl extends BaseControl {

	/** @var Model\Entity\Project */
	protected $project;

	protected $parser;
	protected $interpreter;
	protected $formFactory;


	public function __construct(Model\Entity\Project $project, IParser $parser, IInterpreter $interpreter, IFormFactory $formFactory) {
		$this->project = $project;
		$this->parser = $parser;
		$this->interpreter = $interpreter;
		$this->formFactory = $formFactory;
	}


	protected function createComponentForm() {
		$form = $this->formFactory->create();
		$form->addTextarea('command', 'Command', NULL, 10)
			->setRequired('Enter command.')
			->controlPrototype->addClass('span9');
		$form->addSubmit('send', 'Send');
		$form->onSuccess[] = $this->formSucceeded;
		return $form;
	}

	public function formSucceeded($form) {
		$command = $form['command']->value;
		try {
			$command = $this->parser->parse($command);
		} catch (ParsingException $e) {
			$form->addError('Parsing error: '. $e->getMessage());
			return;
		}

		$this->interpreter->execute($command, $this->project);
		$this->presenter->flashMessage('Command successfully executed.');
		$this->redirect('this');
	}


	public function render() {
		$this['form']->render();
	}

}
