<?php

use Nette\Security\User;
use Nette\Utils\Arrays;


/** Main menu */
class MenuControl extends BaseControl
{

	/** @var array of (link => string, args => array, label => string) */
	protected $links = array();

	/** @var Nette\Security\User */
	protected $user;


	/**
	 * @param array of (link => string, args => array, label => string)
	 * @param User
	 */
	public function __construct(array $links, User $user)
	{
		$this->links = $links;
		$this->user = $user;
	}


	/** @return void */
	public function render()
	{
		$links = array();
		foreach ($this->links as $l) {
			$link = array(
				'link' => Arrays::get($l, 'link', ''),
				'args' => Arrays::get($l, 'args', array()),
				'label' => Arrays::get($l, 'label', ''),
			);
			if (!array_key_exists('access', $l))
				$links[] = $link;
			else {
				if ($l['access'] instanceof Nette\Callback)
					$allowed = $l['access']->invoke();
				else {
					if (is_array($l['access']))
						list($resource, $privilege) = $l['access'];
					else
						list($resource, $privilege) = array($l['access'], NULL);
					if ($resource === '') $resource = NULL;
					$allowed = $this->user->isAllowed($resource, $privilege);
				}
				if ($allowed xor !empty($l['accessInverse']))
					$links[] = $link;
			}
		}
		$this->template->links = $links;
		parent::render();
	}

}
