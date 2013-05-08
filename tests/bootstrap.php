<?php

if (@!(include __DIR__ . '/../libs/autoload.php') || @!(include __DIR__ . '/../libs/nette/tester/Tester/bootstrap.php')) {
	echo 'Install Nette Tester using `composer update --dev`';
	exit(1);
}


// configure environment
Tester\Helpers::setup();
date_default_timezone_set('Europe/Prague');


function id($val) {
	return $val;
}


function createTempDir() {
	$dir = __DIR__ . '/../temp/tests/' . getmypid();
	@mkdir(dirname($dir));
	Tester\Helpers::purge($dir);
	return $dir;
}


$configurator = new Nette\Config\Configurator;
$configurator->setDebugMode(FALSE);
$configurator->setTempDirectory(__DIR__ . '/../temp');

$configurator->createRobotLoader()
	->addDirectory(__DIR__ . '/../app')
	->register();

$configurator->addConfig(__DIR__ . '/../app/config/config.neon', $configurator::NONE);
$configurator->addConfig(__DIR__ . '/config.neon', $configurator::NONE); // none section

$configurator->onCompile[] = function ($configurator, $compiler) {
    $compiler->addExtension('modules', new VojtechDobes\ExtensionsList);
};

return $configurator->createContainer();
