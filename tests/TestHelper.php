<?php
use Phalcon\DI,
    Phalcon\DI\FactoryDefault;

ini_set('display_errors', 1);

// To avoid Strict standards: Declaration of Test\UnitTestCase::setUp() should be compatible with
// PHPUnit_Framework_TestCase::setUp() in /var/www/musdiff/tests/UnitTestCase.php on line 10
error_reporting(E_ALL & ~E_STRICT);

defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(__DIR__ . '/..'));
defined('DATA_PATH') || define('DATA_PATH', realpath(__DIR__ . '/../data'));
defined('VENDOR_PATH') || define('VENDOR_PATH', realpath(__DIR__ . '/../vendor'));
defined('PUBLIC_PATH') || define('PUBLIC_PATH', __DIR__);

defined('ENTRY_POINT') || define('ENTRY_POINT', 'phpunit');

chdir(dirname(__DIR__));
require 'init_autoloader.php';

$loader = new \Phalcon\Loader();
$loader->registerDirs([
    APPLICATION_PATH
]);
$loader->register();

$di = new FactoryDefault();
DI::reset();

// Add any needed services to the DI here.

DI::setDefault($di);
