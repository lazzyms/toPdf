<?php

use Phalcon\Config;

$variable = array(
    'binary' => 'C:\\Program Files\\wkhtmltopdf\\bin\\wkhtmltopdf.exe',
    'base_url' => '/pdfgen/',
    'whitelist_ip' => ['::1', '127.0.0.1'],
    'debug' => false,
    'chrome_path' => 'start C:\"Program Files (x86)"\Google\Chrome\Application\chrome.exe',
    'temp_dest' => APP_PATH . '\tempfiles',
);

$config = new Config($variable);

return $config;
