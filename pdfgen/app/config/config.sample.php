<?php

use Phalcon\Config;

$variable = array(
    'binary'=>'C:\\Program Files\\wkhtmltopdf\\bin\\wkhtmltopdf.exe',
    'base_url' => '/pdfgen/',
    'whitelist_ip'=> ['::1','127.0.0.1'],
    'debug' => false
);

$config = new Config($variable);

return $config;