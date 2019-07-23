<?php

require 'includes/bootstrap.php';

require 'scripts/users.php';
require 'scripts/categories.php';

$flarum
    ->query('SET FOREIGN_KEY_CHECKS=1;')
    ->exec();

echo 'Enabled Foreing Key Checks.'.PHP_EOL;

echo sprintf(
    'Completed migration operation in %s seconds.',
    number_format(microtime(true) - $timestampStart, 4)
).PHP_EOL;
