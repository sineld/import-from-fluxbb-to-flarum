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

function GetRandomColor()
{
    return '#'.str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}
