<?php

function sluggify($value)
{
    $tr = ['ş', 'Ş', 'ı', 'I', 'İ', 'ğ', 'Ğ', 'ü', 'Ü', 'ö', 'Ö', 'Ç', 'ç', '(', ')', '/', ':', ','];
    $eng = ['s', 's', 'i', 'i', 'i', 'g', 'g', 'u', 'u', 'o', 'o', 'c', 'c', '', '', '-', '-', ''];

    $value = str_replace($tr, $eng, $value);
    $value = preg_replace('/£/', ' pound ', $value);
    $value = preg_replace('/#/', ' hash ', $value);
    $value = preg_replace("/[\-+]/", ' ', $value);
    $value = preg_replace("/[\s+]/", ' ', $value);
    $value = preg_replace("/[\.+]/", '', $value);
    $value = preg_replace("/[^A-Za-z0-9\.\s]/", '', $value);
    $value = preg_replace("/[\s]/", '-', $value);
    $value = preg_replace("/\-\-+/", '-', $value);

    $value = strtolower($value);

    if (substr($value, -1) == '-') {
        $value = substr($value, 0, -1);
    }
    if (substr($value, 0, 1) == '-') {
        $value = substr($value, 1);
    }

    return $value;
}

function getRandomColor()
{
    return '#'.str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}

function getUserId($username)
{
    global $fluxbb;

    if (!preg_match('/^[a-zA-Z0-9-_]+$/', $username)) {
        $username = sluggify($username);
    }

    $user = $fluxbb
        ->select('id')
        ->table('users')
        ->where('username', $username)
        ->get();

    return $user ? $user->id : 2;
}

function dd($string)
{
    die(
        var_dump($string)
    );
}

function timestampToDatetime($time)
{
    return date('Y-m-d H:i:s', intval($time));
}

function convertFluxbbLinksToFlarum($text)
{
    global $flarum, $fluxbb;

    $text = preg_replace('/viewtopic\.php\?id=([0-9]+)&p=[0-9]+|viewtopic\.php\?id=([0-9]+)/', 'd/$1$2', $text);

    $text = preg_replace('/profile\.php\?id=([0-9]+)/', 'u/$1', $text);

    $text = preg_replace_callback(
        '/viewtopic\.php\?pid=([0-9]+)#p[0-9]+|viewtopic\.php\?pid=([0-9]+)/',
        function ($matches) use ($flarum) {
            $pid = $matches[1] ?: $matches[0];

            $post = $flarum
                ->table('posts')
                ->where('id', $pid)
                ->get();

            if (isset($post->discussion_id)) {
                $discussionId = intval($post->discussion_id);
                $number = intval($post->number);

                return "d/$discussionId/$number";
            }

            return;
        },
        $text
    );

    $text = preg_replace_callback(
        '/viewforum\.php\?id=([0-9]+)&p=[0-9]+|viewforum\.php\?id=([0-9]+)/',
        function ($matches) use ($fluxbb) {
            $id = $matches[1];
            if (isset($matches[2])) {
                $id = $matches[2];
            }

            $forum = $fluxbb
                ->select('forum_name')
                ->table('forums')
                ->where('id', $id)
                ->get();

            $slug = sluggify($forum->forum_name);

            return "t/$slug";
        },
        $text
    );

    return str_replace('http://forum.laravel.gen.tr', 'https://laravel.gen.tr', $text);
}

function replaceUnsupportedMarks($text)
{
    $q = '\[q]|\[\/q]';
    $em = '\[em]|\[\/em]';
    $ins = '\[ins]|\[\/ins]';
    $sup = '\[sup]|\[\/sup]';
    $sub = '\[sub]|\[\/sub]';
    $video = '\[video]|\[\/video]';
    $left = '\[left]|\[\/left]';
    $right = '\[right]|\[\/right]';
    $justify = '\[justify]|\[\/justify]';
    $regex = "/$q|$em|$ins|$sup|$sub|$video|$left|$right|$justify/";

    $text = preg_replace('#\[h](.+)\[\/h]#i', '[b][size=20]$1[/size][/b]', $text);
    $text = preg_replace('/\[acronym=.+](.+)\[\/acronym]|\[acronym]|\[\/acronym]/', '$1', $text);
    $text = preg_replace($regex, '', $text);

    return $text;
}
