<?php
use Phalcon\DI,
    Phalcon\DI\FactoryDefault;

ini_set('display_errors',1);
error_reporting(E_ALL);

define('ROOT_PATH', __DIR__);

// use the application autoloader to autoload the classes
// autoload the dependencies found in composer
$loader = new \Phalcon\Loader();

$loader->registerDirs(array(
                           ROOT_PATH,
                      ));
$loader->registerNamespaces(
	array (
		'Peregrin' => ROOT_PATH . '/../Peregrin/',
		'Phalcon' => ROOT_PATH . '/incubator/Library/Phalcon/',
	)
);
$loader->register();

$di = new FactoryDefault();
DI::reset();

// add any needed services to the DI here

DI::setDefault($di);
