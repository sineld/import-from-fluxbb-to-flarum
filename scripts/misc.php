<?php

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

 $tags = $flarum
    ->select('id')
    ->table('tags')
    ->getAll();

foreach ($tags as $tag) {
    $lastDiscussion = $flarum
        ->select('p.*', 'dt.*')
        ->table('discussion_tag AS dt')
        ->join('posts AS p', 'dt.discussion_id', 'p.discussion_id')
        ->where('dt.tag_id', $tag->id)
        ->orderBy('created_at', 'DESC')
        ->limit(1)
        ->get();

    $flarum
        ->table('tags')
        ->where('id', $tag->id)
        ->update([
            'last_posted_at' => empty($lastDiscussion->created_at) ? null : $lastDiscussion->created_at,
            'last_posted_discussion_id' => empty($lastDiscussion->created_at) ? null : $lastDiscussion->discussion_id,
        ]);

    $tagCount = $flarum
        ->table('discussion_tag')
        ->count('tag_id', 'total')
        ->where('tag_id', $tag->id)
        ->get();

    $flarum
        ->table('tags')
        ->where('id', $tag->id)
        ->update([
            'discussion_count' => $tagCount->total,
        ]);
}

echo 'Completed the update posts last_posted_at and last_posted_discussion_id values...'.PHP_EOL;

echo 'Converting fluxbb http(s) links...'.PHP_EOL;
$posts = $flarum
    ->table('posts')
    ->getAll();

foreach ($posts as $post) {
    $flarum2
        ->table('posts')
        ->where('id', $post->id)
        ->update([
            'content' => convertFluxbbLinksToFlarum($post->content),
        ]);
}

echo 'Converted fluxbb http(s) links...'.PHP_EOL;
