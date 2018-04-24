<?php

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Nette\Configurator;

$configurator->setDebugMode('146.102.123.42'); // enable for your remote IP
$configurator->setDebugMode('46.28.106.68'); // enable for your remote IP
$configurator->setDebugMode('2a02:2b88:1:4::b0'); // enable for your remote IP
$configurator->enableTracy(__DIR__ . '/../log');
$configurator->setDebugMode(TRUE);

$configurator->setTimeZone('Europe/Prague');
$configurator->setTempDirectory(__DIR__ . '/../temp');

$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->register();

$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/config.local.neon');

$container = $configurator->createContainer();

return $container;
