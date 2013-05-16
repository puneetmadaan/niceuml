<?php

namespace Model\Security;

use Model\Entity\Base as BaseEntity,
	Nette,
	Nette\Security\IAuthorizator,
	Nette\Security\Permission,
	Nette\Security\User;


/**
 * ACL-based authorizing. Wrapper to Nette\Permission. Works with entities.
 *
 * @author Jakub Červenka
 */
class Authorizator extends Nette\Object implements IAuthorizator
{

	/** @var \Nette\Security\User */
	private $user;
	private $acl;


	/**
	* @param \Nette\Security\User
	*/
	public function __construct(User $user)
	{
		$this->user = $user;
		$acl = new Permission;

		$acl->addRole('guest');
		$acl->addRole('authenticated');
		$acl->addRole('user','authenticated');
		$acl->addRole('admin','authenticated');

		$acl->addResource('usage');
		$acl->allow('user', 'usage');

		$acl->addResource('project');
		$acl->allow('user', 'project', 'view', $this->checkProjectUser);
		$acl->allow('user', 'project', $acl::ALL, $this->checkProjectOwner);

		$acl->addResource('element');
		$acl->allow('user', 'element', $acl::ALL, $this->checkElementProject);

		$acl->addResource('relation');
		$acl->allow('user', 'relation', $acl::ALL, $this->checkRelationElement);

		$acl->addResource('diagram');
		$acl->allow('user', 'diagram', $acl::ALL, $this->checkDiagramProject);

		$acl->allow('admin');

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
		if ($resource instanceof BaseEntity) {
			$resource = new EntityResource($resource);
		}
		return $this->acl->isAllowed($role, $resource, $privilege);
	}


	public function checkProjectUser($acl, $role, $resource, $privilege, $owner = FALSE) {
		if (!$acl->queriedResource instanceof EntityResource)
			return TRUE;
		$project = $acl->queriedResource->entity;
		$part = $project->related('user_project')->where('user_id', $this->user->id)->fetch();
		if (!$part) return FALSE;
		return !$owner || $part->role === 'owner';
	}


	public function checkProjectOwner($acl, $role, $resource, $privilege) {
		return $this->checkProjectUser($acl, $role, $resource, $privilege, TRUE);
	}


	public function checkElementProject($acl) {
		if (!$acl->queriedResource instanceof EntityResource)
			return TRUE;
		$element = $acl->queriedResource->entity;
		return (bool) $element->project->related('user_project')->where('user_id', $this->user->id)->fetch();
	}


	public function checkRelationElement($acl) {
		if (!$acl->queriedResource instanceof EntityResource)
			return TRUE;
		$relation = $acl->queriedResource->entity;

		// start->project and end->project projects are the same
		return (bool) $relation->start->project->related('user_project')->where('user_id', $this->user->id)->fetch();
	}


	public function checkDiagramProject($acl) {
		if (!$acl->queriedResource instanceof EntityResource)
			return TRUE;
		$diagram = $acl->queriedResource->entity;
		return (bool) $diagram->project->related('user_project')->where('user_id', $this->user->id)->fetch();
	}

}
