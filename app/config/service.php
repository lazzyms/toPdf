<?php

$di->setShared('config', function () {
    return include APP_PATH . '/config/config.php';
});
$config = $di->getConfig();


// $di->set('config', function() use ($config) {
//     return $config;
//     }, true);