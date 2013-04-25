<?php

namespace Model;


interface ISourceModel {

	function loadSource($source);

	function dumpSource($entity);

}
