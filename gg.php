<?php
function __autoload($className) {
    require_once("php-classes/$className.php");
}

$app = Application::getInstance();

var_dump($app);