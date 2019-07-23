<?php

$flarum
    ->query('TRUNCATE TABLE users;')
    ->exec();

echo 'Truncated users of flarum.'.PHP_EOL;

$oldUsers = $fluxbb
    ->select('id, username, email, registered, last_visit, signature, num_posts')
    ->table('users')
    ->getAll();

echo 'Migrating '.count($oldUsers).' users...'.PHP_EOL;

$importedUsersCount = 0;
foreach ($oldUsers as $user) {
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
                'joined_at' => date('Y-m-d H:i:s', intval($user->registered)),
                'last_seen_at' => date('Y-m-d H:i:s', intval($user->last_visit)),
                'bio' => $user->signature,
                'comment_count' => $user->num_posts,
                'discussion_count' => 0,
            ]);
        ++$importedUsersCount;
    }
}

echo 'Migrated '.$importedUsersCount.' users...'.PHP_EOL;
