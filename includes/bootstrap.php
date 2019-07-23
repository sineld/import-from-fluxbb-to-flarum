<?php

require 'pdox.php';
require 'functions.php';
require 'config.php';

$flarum
    ->query('SET FOREIGN_KEY_CHECKS=0;')
    ->exec();

echo 'Disabled Foreing Key Checks.'.PHP_EOL;
