<?php

ob_start();

// -----

// Error displaying (comment the following to turn on error displaying)

error_reporting(0);

// Constants

define('ROOT_DIR', dirname(__FILE__));

define('IS_LIVECHAT', true);
include(dirname(dirname(ROOT_DIR)) . '/wp-config.php');

// Imports
require_once ROOT_DIR . '/lib/timezone.php';
require_once 'lib/error_handler.php';
require_once 'lib/magic_quotes_fix.php';
require_once 'lib/autoload.php';
require_once ROOT_DIR . '/lib/timezone.php';
// Run the application

$application = new Application();
$application->config();

$response = $application->run(new Request());
$response->send();

// -----

ob_end_flush();

?>
