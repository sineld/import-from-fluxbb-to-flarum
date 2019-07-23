<?php

set_time_limit(0);
ini_set('memory_limit', -1);

$timestampStart = microtime(true);

$fluxbb = new Pdox([
    'host' => 'localhost',
    'driver' => 'mysql',
    'database' => 'fluxbb',
    'username' => 'homestead',
    'password' => 'secret',
    'charset' => 'utf8',
    'collation' => 'utf8_general_ci',
    'prefix' => '',
]);

$flarum = new Pdox([
    'host' => 'localhost',
    'driver' => 'mysql',
    'database' => 'flarum',
    'username' => 'homestead',
    'password' => 'secret',
    'charset' => 'utf8',
    'collation' => 'utf8_general_ci',
    'prefix' => '',
]);

$flarum2 = new Pdox([
    'host' => 'localhost',
    'driver' => 'mysql',
    'database' => 'flarum',
    'username' => 'homestead',
    'password' => 'secret',
    'charset' => 'utf8',
    'collation' => 'utf8_general_ci',
    'prefix' => '',
]);

$smileys = [
    ['smile.png', ':)'],
    ['neutral.png', ':|'],
    ['sad.png', ':('],
    ['big_smile.png', ':D'],
    ['yikes.png', ':o'],
    ['wink.png', ';)'],
    ['hmm.png', ':/'],
    ['tongue.png', ':P'],
    ['monkey.png', ':O)'],
    ['monkey-glass.png', '8o)'],
    ['monkey-smile.png', ':oD'],
    ['noel.gif', ':noel:'],
    ['lol.png', ':lol:'],
    ['mad.png', ':mad:'],
    ['roll.png', ':rolleyes:'],
    ['cool.png', ':cool:'],
];
