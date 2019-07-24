<?php

// $flarum
//     ->query('TRUNCATE TABLE users;')
//     ->exec();

// $flarum
//     ->table('users')
//     ->where('id', '>', 1)
//     ->delete();

// echo 'Truncated users of flarum.'.PHP_EOL;

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
                'password' => bin2hex(random_bytes(20)),
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
