<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf9086cc1f627b4bcf0b4e5a9384ee01d
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'MathPHP\\' => 8,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'MathPHP\\' => 
        array (
            0 => __DIR__ . '/..' . '/markrogoyski/math-php/src',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf9086cc1f627b4bcf0b4e5a9384ee01d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf9086cc1f627b4bcf0b4e5a9384ee01d::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
