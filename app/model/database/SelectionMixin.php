<?php

namespace Model\Database;

use Nette;


class SelectionMixin
{


	/**
	 * Static class - cannot be instantiated.
	 */
	final public function __construct()
	{
		throw new Nette\StaticClassException;
	}


	public static function collect($_this, $item, $preserveKeys = FALSE)
	{
		$result = array();

		if (is_array($item) || $item instanceof \Closure || $item instanceof Nette\Callback)
			$cb = \callback($item);
		else $cb = FALSE;

		foreach ($_this as $key => $row) {
			$value = $cb ? $cb($row) : $row->$item;
			if ($preserveKeys)
				$result[$key] = $value;
			else
				$result[] = $value;
		}

		return $result;
	}

}
