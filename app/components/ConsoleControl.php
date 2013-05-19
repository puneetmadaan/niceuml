<?php


class ConsoleControl extends BaseControl {

	/** @var Model\Entity\Project */
	protected $project;

	/** @var Model\ICommandModel */
	protected $model;

	/** @var FormFactory */
	protected $formFactory;


	public function __construct(Model\ICommandModel $model, FormFactory $formFactory)
	{
		$this->model = $model;
		$this->formFactory = $formFactory;
	}


	public function setProject(Model\Entity\Project $project)
	{
		$this->project = $project;
	}


	protected function createComponentForm()
	{
		$form = $this->formFactory->create();
		$form->addTextarea('command', 'Command', NULL, 10)
			->setRequired('Enter command.')
			->controlPrototype->addClass('span9');
		$form->addSubmit('send', 'Send');
		$form->onSuccess[] = $this->formSucceeded;
		return $form;
	}


	public function formSucceeded($form)
	{
		$command = $form['command']->value;

		try {
			$result = $this->model->execute($command, $this->project);
			if (!$result) {
				$form->addError('Unrecognized command.');
				return;
			}
		} catch (CommandException $e) {
			$form->addError('Console error: '. $e->getMessage());
			return;
		} catch (Exception $e) {
			$form->addError('Unknown error occured.');
			Nette\Diagnostics\Debugger::log($e);
			return;
		}


		$this->presenter->flashMessage('Command successfully executed.');
		$this->redirect('this');
	}


	public function render() {
		$this['form']->render();
	}

}
