<?php

use s9e\TextFormatter\Bundles\Forum as TextFormatter;

// $flarum
//     ->query('DELETE FROM posts;')
//     ->exec();

// $flarum
//     ->query('DELETE FROM discussion_tag;')
//     ->exec();

// $flarum
//     ->query('DELETE FROM discussions;')
//     ->exec();

// echo 'Truncated posts of flarum.'.PHP_EOL;

$topics = $fluxbb
    ->table('topics')
    ->getAll();

echo 'Migrating '.count($topics).' posts...'.PHP_EOL;

$importedPostsCount = 0;
$importedDiscussionsCount = 0;
foreach ($topics as $topic) {
    $totalPostsInDiscussion = (intval($topic->num_replies) + 1);

    $posts = $fluxbb
        ->table('posts')
        ->where('topic_id', $topic->id)
        ->orderBy('id')
        ->getAll();

    $currentPostNumber = 0;
    $participantsList = [];

    foreach ($posts as $post) {
        ++$importedPostsCount;
        ++$currentPostNumber;
        $userId = $post->poster_id;

        if (!in_array($userId, $participantsList)) {
            $participantsList[] = $userId;
        }

        $content = $post->message;

        foreach ($smileys as $smiley) {
            $quotedSmiley = preg_quote($smiley[1], '#');
            $match = '#(?<=\s|^)('.$quotedSmiley.')(?=\s|$)#';
            $content = preg_replace($match, '[img]/assets/images/smileys/'.$smiley[0].'[/img]', $content);
        }

        $content = TextFormatter::parse(replaceUnsupportedMarks($content));

        $flarum
            ->table('posts')
            ->insert([
                'id' => $post->id,
                'discussion_id' => $post->topic_id,
                'number' => $currentPostNumber,
                'created_at' => timestampToDatetime($post->posted),
                'user_id' => $userId,
                'type' => 'comment',
                'content' => $content,
                'edited_at' => $post->edited ? timestampToDatetime($post->edited) : null,
                'edited_user_id' => $post->edited_by ? getUserID($post->edited_by) : null,
                'is_approved' => 1,
                'ip_address' => !empty($post->poster_ip) ? $post->poster_ip : null,
            ]);
    }

    $flarum
        ->table('discussions')
        ->insert([
            'id' => $topic->id,
            'title' => $topic->subject,
            'comment_count' => $totalPostsInDiscussion,
            'participant_count' => count($participantsList),
            'post_number_index' => $totalPostsInDiscussion,
            'created_at' => timestampToDatetime($topic->posted),
            'user_id' => getUserID($topic->poster),
            'first_post_id' => $topic->first_post_id,
            'last_posted_at' => timestampToDatetime($topic->last_post),
            'last_posted_user_id' => getUserID($topic->last_poster),
            'last_post_id' => intval($topic->last_post_id),
            'last_post_number' => $totalPostsInDiscussion,
            'slug' => sluggify($topic->subject),
            'is_approved' => 1,
            'is_locked' => intval($topic->closed),
            'is_sticky' => intval($topic->sticky),
        ]);

    $category = $fluxbb
            ->select('cat_id')
            ->table('forums')
            ->where('id', $topic->forum_id)
            ->get();

    $flarum
        ->table('discussion_tag')
        ->insert([
            'discussion_id' => $topic->id,
            'tag_id' => $category->cat_id,
        ]);

    ++$importedDiscussionsCount;
}

echo 'Migrated '.$importedPostsCount.' posts...'.PHP_EOL;
echo 'Migrated '.$importedDiscussionsCount.' discussions...'.PHP_EOL;
