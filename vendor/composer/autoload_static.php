<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitae60ef478ceff515f1a7d9a301af7601
{
    public static $prefixLengthsPsr4 = array (
        's' => 
        array (
            's9e\\TextFormatter\\' => 18,
            's9e\\RegexpBuilder\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        's9e\\TextFormatter\\' => 
        array (
            0 => __DIR__ . '/..' . '/s9e/text-formatter/src',
        ),
        's9e\\RegexpBuilder\\' => 
        array (
            0 => __DIR__ . '/..' . '/s9e/regexp-builder/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitae60ef478ceff515f1a7d9a301af7601::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitae60ef478ceff515f1a7d9a301af7601::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
