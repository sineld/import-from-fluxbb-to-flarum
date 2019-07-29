<?php

$users = $fluxbb
    ->select('id, username, email, registered, last_visit, signature, num_posts')
    ->table('users')
    ->getAll();

echo 'Migrating '.count($users).' users...'.PHP_EOL;

$importedUsersCount = 0;
foreach ($users as $user) {
    if ($user->username != 'Guest') {
        $flarum
            ->table('users')
            ->insert([
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'is_email_confirmed' => 1,
                'password' => '$2y$10$ZtFL1whjJKjh3dpW07H5De1TlwCjk9nvBiFa2UZfvotWO8hpbSadO',
                'avatar_url' => null,
                'joined_at' => timestampToDatetime($user->registered),
                'last_seen_at' => timestampToDatetime($user->last_visit),
                'bio' => $user->signature,
                'comment_count' => 0,
                'discussion_count' => 0,
            ]);
        ++$importedUsersCount;
    }
}

echo 'Migrated '.$importedUsersCount.' users...'.PHP_EOL;
