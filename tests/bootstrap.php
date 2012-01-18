<?php

$vendorDir = __DIR__.'/../vendor';
require_once $vendorDir.'/symfony/src/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Molino'                 => array(__DIR__.'/../src', __DIR__),
    'Symfony'                => $vendorDir.'/symfony/src',
    'Pagerfanta'             => $vendorDir.'/pagerfanta/src',
    'Mandango\\Mondator'     => $vendorDir.'/mondator/src',
    'Mandango'               => $vendorDir.'/mandango/src',
    'Doctrine\\Common'       => $vendorDir.'/doctrine-common/lib',
    'Doctrine\\MongoDB'      => $vendorDir.'/doctrine-mongodb/lib',
    'Doctrine\\ODM\\MongoDB' => $vendorDir.'/doctrine-mongodb-odm/lib',
    'Doctrine\\DBAL'         => $vendorDir.'/doctrine-dbal/lib',
    'Doctrine\\ORM'          => $vendorDir.'/doctrine-orm/lib',
    'Model'                  => __DIR__,
));
$loader->registerPrefixes(array(
    'Twig_' => $vendorDir.'/twig/lib',
));
$loader->register();

/*
 * Generate Mandango model.
 */
$configClasses = array(
    'Model\Mandango\Article' => array(
        'fields' => array(
            'title' => array('type' => 'string'),
        ),
    ),
);

use Mandango\Mondator\Mondator;

$mondator = new Mondator();
$mondator->setConfigClasses($configClasses);
$mondator->setExtensions(array(
    new Mandango\Extension\Core(array(
        'metadata_factory_class'  => 'Model\Mandango\Mapping\Metadata',
        'metadata_factory_output' => __DIR__.'/Model/Mandango/Mapping',
        'default_output'          => __DIR__.'/Model/Mandango',
    )),
));
$mondator->process();
