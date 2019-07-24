<?php

$timestampStart = microtime(true);
@system('clear');
require 'includes/bootstrap.php';
require_once 'vendor/autoload.php';

$flarum
    ->query('SET GLOBAL FOREIGN_KEY_CHECKS=0;')
    ->exec();

echo 'Disabled Global Foreing Key Checks.'.PHP_EOL;

$tables = [
    'users',
    'tags',
    'posts',
    'discussions',
    'discussion_tag',
    'discussion_user',
    'groups',
    'group_user',
];

foreach ($tables as $table) {
    $flarum
        ->query("TRUNCATE TABLE {$table};")
        ->exec();

    echo "Truncated {$table} table.".PHP_EOL;
}

require 'scripts/users.php';
require 'scripts/categories.php';
require 'scripts/forums.php';
require 'scripts/topics-posts.php';
require 'scripts/groups.php';
require 'scripts/subscriptions.php';
require 'scripts/misc.php';

$flarum
    ->query('SET GLOBAL FOREIGN_KEY_CHECKS=1;')
    ->exec();

echo 'Enabled Global Foreing Key Checks.'.PHP_EOL;

echo sprintf(
    'Completed migration operation in %s seconds.',
    number_format(microtime(true) - $timestampStart, 2)
).PHP_EOL;
