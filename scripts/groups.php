<?php

$flarum
    ->query('DELETE FROM group_user;')
    ->exec();

$flarum
    ->query('DELETE FROM groups;')
    ->exec();

echo 'Truncated groups of flarum.'.PHP_EOL;

$groups = $fluxbb
    ->table('groups')
    ->getAll();

echo 'Migrating '.count($groups).' groups...'.PHP_EOL;

$importedGroupsCount = 0;
foreach ($groups as $group) {
    $groupId = $group->g_id;
    $icon = null;

    if ($groupId == 1) {
        $icon = 'wrench';
    } elseif ($groupId == 2) {
        $groupId = 4;
        $icon = 'bolt';
    } elseif ($groupId == 4) {
        $groupId = 3;
    } elseif ($groupId == 3) {
        $groupId = 2;
    }

    // not yet
    $users = $fluxbb
        ->table('users')
        ->where('group_id', $group->g_id)
        ->getAll();

    if ($groupId != 3) {
        foreach ($users as $user) {
            $flarum
                ->table('group_user')
                ->insert([
                    'user_id' => $user->id,
                    'group_id' => $groupId,
                ]);
        }
    }

    $flarum
        ->table('groups')
        ->insert([
            'name_singular' => $group->g_user_title,
            'name_plural' => $group->g_title,
            'color' => getRandomColor(),
            'icon' => $icon,
        ]);

    ++$importedGroupsCount;
}

echo 'Migrated '.$importedGroupsCount.' posts...'.PHP_EOL;
