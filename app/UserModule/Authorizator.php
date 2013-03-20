<?php

namespace UserModule;

use Nette;
use Nette\Security;

/**
 * ACL-based authorizing. Wrapper to Nette\Permission. Works with Ndab Entities.
 * 
 * @author Jakub Červenka
 */
class Authorizator extends Nette\Object implements Security\IAuthorizator {

	/** @var \Nette\Security\User */
	private $user;
	private $acl;


	/**
	* @param \Nette\Security\User
	*/
	public function __construct(\Nette\Security\User $user)
	{
		$this->user = $user;
		$acl = new Security\Permission;
		
        $acl->addRole('guest');
        $acl->addRole('authenticated');
        $acl->addRole('user','authenticated');
        $acl->addRole('admin','authenticated');

		$acl->allow('user');

		$this->acl = $acl;
	}


	/**
	 * Returns TRUE if and only if the Role has access to [certain $privileges upon] the Resource.
	 *
	 * This method checks Role inheritance using a depth-first traversal of the Role list.
	 * The highest priority parent (i.e., the parent most recently added) is checked first,
	 * and its respective parents are checked similarly before the lower-priority parents of
	 * the Role are checked.
	 *
	 * @param  string|Permission::ALL|IRole  role
	 * @param  string|Permission::ALL|IResource  resource
	 * @param  string|Permission::ALL  privilege
	 * @throws Nette\InvalidStateException
	 * @return bool
	 */
	public function isAllowed($role = self::ALL, $resource = self::ALL, $privilege = self::ALL) {
		if ($resource instanceof \Model\Entity) {
			$resource = new EntityResource($resource);
		}
		return $this->acl->isAllowed($role, $resource, $privilege);
	}

}