<?php
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(__DIR__));
defined('DATA_PATH') || define('DATA_PATH', realpath(__DIR__ . '/data'));
defined('VENDOR_PATH') || define('VENDOR_PATH', realpath(__DIR__ . '/vendor'));
defined('PUBLIC_PATH') || define('PUBLIC_PATH', __DIR__ . '/public');

defined('ENTRY_POINT') || define('ENTRY_POINT', 'cli');

if (!extension_loaded('phalcon')) {
    exit('Phalcon extension is not installed. See http://phalconphp.com/en/download');
}

require 'init_autoloader.php';

/**
 * Process the console arguments
 */
$arguments = array();
$params = array();

foreach ($argv as $k => $arg) {
    if ($k == 1) {
        $arguments['route'] = $arg;
    } elseif ($k >= 2) {
        $params[] = $arg;
    }
}
if (count($params) > 0) {
    $arguments['params'] = $params;
}

use Core\CLI\Application;

$application = Application::init(require './config/application.config.php');

try {
    $application->handle($arguments);
} catch (Exception $e) {
    echo $e->getMessage();
    exit(255);
}
