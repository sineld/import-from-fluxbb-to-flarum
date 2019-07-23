<?php

$forums = $fluxbb
    ->select('id, forum_name, forum_desc, disp_position, cat_id')
    ->table('forums')
    ->getAll();

echo 'Migrating '.count($forums).' tags...'.PHP_EOL;

$importedTagsCount = 0;
foreach ($forums as $forum) {
    $flarum
        ->table('tags')
        ->insert([
            'name' => $forum->forum_name,
            'slug' => sluggify($forum->forum_name),
            'description' => $forum->forum_desc,
            'color' => getRandomColor(),
            'position' => intval($forum->disp_position),
            'parent_id' => intval($forum->cat_id),
        ]);
    ++$importedTagsCount;
}

echo 'Migrated '.$importedTagsCount.' forums...'.PHP_EOL;
