<?php

$id = 3375;
$lastDiscussion = $flarum
    ->select('p.*', 'dt.*')
    ->table('discussion_tag AS dt')
    ->join('posts AS p', 'dt.discussion_id', 'p.discussion_id')
    ->where('dt.discussion_id', $id)
    ->limit(1)
    ->get();

// SELECT
// 	p.*,
// 	dt.*
// FROM
// 	posts as p
// JOIN discussion_tag AS dt
//     ON p.discussion_id = dt.discussion_id
// WHERE dt.discussion_id = 3375
// ORDER BY p.created_at DESC
// LIMIT 1;

var_dump($lastDiscussion);

die;

$users = $flarum
    ->select('id')
    ->table('users')
    ->getAll();

echo 'Starting the update posts counters and counters discussions...'.PHP_EOL;

foreach ($users as $user) {
    $numberOfPosts = $flarum
        ->table('posts')
        ->count('id', 'total')
        ->where('user_id', $user->id)
        ->get();

    $numberOfDiscussions = $flarum
        ->count('id', 'total')
        ->table('discussions')
        ->where('user_id', $user->id)
        ->get();

    $flarum
        ->table('users')
        ->where('id', $user->id)
        ->update([
            'discussion_count' => $numberOfDiscussions->total,
            'comment_count' => $numberOfPosts->total,
        ]);
}

echo 'Completed the update posts counters and counters discussions...'.PHP_EOL;

$tags = $fluxbb
    ->select('id')
    ->table('tags')
    ->getAll();

foreach ($tags as $tag) {
    $tagId = $tag->id;
    // $numberOfPosts = $flarum
    //     ->table('posts')
    //     ->count('id', 'total')
    //     ->where('user_id', $user->id)
    //     ->get();

    // $numberOfDiscussions = $flarum
    //     ->count('id', 'total')
    //     ->table('discussions')
    //     ->where('user_id', $user->id)
    //     ->get();

    // $flarum
    //     ->table('users')
    //     ->where('id', $user->id)
    //     ->update([
    //         'discussion_count' => $numberOfDiscussions->total,
    //         'comment_count' => $numberOfPosts->total,
    //     ]);
}
