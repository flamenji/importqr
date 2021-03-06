<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit909af3f9ca1692af18b94c1c2f2e48c8
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\SimpleCache\\' => 16,
            'PhpOffice\\PhpSpreadsheet\\' => 25,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\SimpleCache\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/simple-cache/src',
        ),
        'PhpOffice\\PhpSpreadsheet\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpoffice/phpspreadsheet/src/PhpSpreadsheet',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit909af3f9ca1692af18b94c1c2f2e48c8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit909af3f9ca1692af18b94c1c2f2e48c8::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
