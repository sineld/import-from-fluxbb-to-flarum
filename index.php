<?php

require 'includes/pdox.php';
require 'config.php';

$fluxbb = new Pdox($fluxbb);
$flarum = new Pdox($flarum);

require 'scripts/users.php';

echo sprintf(
        'Completed migration operation in %s seconds.',
        number_format(microtime(true) - $timestampStart, 4)
    ).PHP_EOL;
