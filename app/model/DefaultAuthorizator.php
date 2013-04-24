<?php

use Nette\Security\IAuthorizator;


class DefaultAuthorizator extends Nette\Object implements IAuthorizator {


	function isAllowed($role, $resource, $privilege) {
		return TRUE;
	}

}
