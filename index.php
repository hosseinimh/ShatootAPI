<?php

require_once(__DIR__ . '/app/Controllers/ShatootController.php');

session_start();
error_reporting(E_ALL);
set_error_handler('Helper::errorHandler');
set_time_limit(30);

$shatootController = new ShatootController();

return $shatootController->updateProducts();
