<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1f47e521aa532623ee8c604463465c1e
{
    public static $classMap = array (
        'Eventviva\\ImageResize' => __DIR__ . '/..' . '/eventviva/php-image-resize/lib/ImageResize.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit1f47e521aa532623ee8c604463465c1e::$classMap;

        }, null, ClassLoader::class);
    }
}
