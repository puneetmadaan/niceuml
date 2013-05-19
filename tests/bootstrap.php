<?php

require __DIR__ . '/../libs/autoload.php';

if (!class_exists('Tester\Assert')) {
	echo 'Install Nette Tester using `composer update --dev`';
	exit(1);
}

Tester\Helpers::setup();

function id($val) {
	return $val;
}


$configurator = new Nette\Config\Configurator;
$configurator->setDebugMode(FALSE);
$configurator->setTempDirectory(__DIR__ . '/../temp');

$configurator->createRobotLoader()
	->addDirectory(__DIR__ . '/../app')
	->register();

$configurator->addConfig(__DIR__ . '/../app/config/config.neon', $configurator::NONE);
$configurator->addConfig(__DIR__ . '/../app/config/modules.neon', $configurator::NONE);
$configurator->addConfig(__DIR__ . '/../app/config/config.local.neon', $configurator::NONE); // none section
$configurator->addConfig(__DIR__ . '/config.neon', $configurator::NONE);

$configurator->onCompile[] = function ($configurator, $compiler) {
    $compiler->addExtension('modules', new VojtechDobes\ExtensionsList);
};

return $configurator->createContainer();
