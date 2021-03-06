<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitb5698824169a0b637d3041f71a6c1ca2
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInitb5698824169a0b637d3041f71a6c1ca2', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader();
        spl_autoload_unregister(array('ComposerAutoloaderInitb5698824169a0b637d3041f71a6c1ca2', 'loadClassLoader'));

        $map = require __DIR__ . '/autoload_namespaces.php';
        foreach ($map as $namespace => $path) {
            $loader->set($namespace, $path);
        }

        $map = require __DIR__ . '/autoload_psr4.php';
        foreach ($map as $namespace => $path) {
            $loader->setPsr4($namespace, $path);
        }

        $classMap = require __DIR__ . '/autoload_classmap.php';
        if ($classMap) {
            $loader->addClassMap($classMap);
        }

        $loader->register(true);

        $includeFiles = require __DIR__ . '/autoload_files.php';
        foreach ($includeFiles as $file) {
            composerRequireb5698824169a0b637d3041f71a6c1ca2($file);
        }
        
        /**
         * When you use 'doctrine' ORM framework (We think to use for annotations),
         * you should syncronize doctrine auto loading process with composer's auto loading mechanisim
         * @author Okan CIRAN
         * @since 24/02/2017
         * @todo Uncomment load registery block below for doctrine when loaded by composer
         */
        /*\Doctrine\Common\Annotations\AnnotationRegistry::registerFile( dirname( __DIR__ ).'/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php' );
        \Doctrine\Common\Annotations\AnnotationRegistry::registerLoader([$loader, 'loadClass']);*/

        return $loader;
    }
}

function composerRequireb5698824169a0b637d3041f71a6c1ca2($file)
{
    require $file;
}
