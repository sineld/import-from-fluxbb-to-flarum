<?php

set_time_limit(0);
ini_set('memory_limit', -1);
ini_set('display_errors', 'On');
ini_set('error_reporting', 'E_ALL');
ini_set('log_errors', 'On');
ini_set('error_log', 'migrate.log');

$timestampStart = microtime(true);

$fluxbb = [
    'host' => 'localhost',
    'driver' => 'mysql',
    'database' => 'fluxbb',
    'username' => 'homestead',
    'password' => 'secret',
    'charset' => 'utf8',
    'collation' => 'utf8_general_ci',
    'prefix' => '',
];

$flarum = [
    'host' => 'localhost',
    'driver' => 'mysql',
    'database' => 'forum',
    'username' => 'homestead',
    'password' => 'secret',
    'charset' => 'utf8',
    'collation' => 'utf8_general_ci',
    'prefix' => '',
];
