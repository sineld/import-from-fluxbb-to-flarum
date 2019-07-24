<?php

$timestampStart = microtime(true);
@system('clear');
require 'includes/bootstrap.php';

require 'scripts/users.php';
require 'scripts/categories.php';
require 'scripts/forums.php';
require 'scripts/topics-posts.php';
require 'scripts/groups.php';
require 'scripts/subscriptions.php';

$flarum
    ->query('SET GLOBAL FOREIGN_KEY_CHECKS=1;')
    ->exec();

echo 'Enabled Global Foreing Key Checks.'.PHP_EOL;

echo sprintf(
    'Completed migration operation in %s seconds.',
    number_format(microtime(true) - $timestampStart, 4)
).PHP_EOL;
