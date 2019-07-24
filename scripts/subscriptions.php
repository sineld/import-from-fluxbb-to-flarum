<?php

// $flarum
//     ->query('DELETE FROM discussion_user;')
//     ->exec();

// echo 'Truncated subscriptions of flarum.'.PHP_EOL;

$subscriptions = $fluxbb
    ->table('topic_subscriptions')
    ->getAll();

echo 'Migrating '.count($subscriptions).' subscriptions...'.PHP_EOL;

$importedSsubscriptionsCount = 0;
foreach ($subscriptions as $subscription) {
    $flarum
        ->table('discussion_user')
        ->insert([
            'user_id' => $subscription->user_id,
            'discussion_id' => $subscription->topic_id,
            'subscription' => 'follow',
        ]);

    ++$importedSsubscriptionsCount;
}

echo 'Migrated '.$importedSsubscriptionsCount.' subscriptions...'.PHP_EOL;
