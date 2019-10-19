<?php

define('TESTS_ROOT', __DIR__);

/** @var \Composer\Autoload\ClassLoader $autoloader */
$autoloader = require TESTS_ROOT . '/../vendor/autoload.php';

// Register test classes
$autoloader->addPsr4('Bloatless\QueryBuilder\Tests\\', TESTS_ROOT);
