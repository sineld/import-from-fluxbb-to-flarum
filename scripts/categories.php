<?php

// $flarum
//     ->query('TRUNCATE TABLE tags;')
//     ->exec();

// echo 'Truncated tags of flarum.'.PHP_EOL;

$categories = $fluxbb
    ->select('id, cat_name, disp_position')
    ->table('categories')
    ->getAll();

echo 'Migrating '.count($categories).' tags...'.PHP_EOL;

$importedTagsCount = 0;
foreach ($categories as $category) {
    $flarum
        ->table('tags')
        ->insert([
            'id' => $category->id,
            'name' => $category->cat_name,
            'slug' => sluggify($category->cat_name),
            'color' => getRandomColor(),
            'position' => intval($category->disp_position),
        ]);
    ++$importedTagsCount;
}

echo 'Migrated '.$importedTagsCount.' categories...'.PHP_EOL;
