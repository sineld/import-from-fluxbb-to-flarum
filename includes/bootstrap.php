<?php

require 'pdox.php';
require 'config.php';
require 'functions.php';

$flarum
    ->query('SET GLOBAL FOREIGN_KEY_CHECKS=0;')
    ->exec();

echo 'Disabled Foreing Key Checks.'.PHP_EOL;
