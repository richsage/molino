<?php

namespace Molino\Tests\Doctrine\ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\Tools\SchemaTool;
use Model\Doctrine\ORM\Article;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $entityManager;

    protected function setUp()
    {
        $configuration = new Configuration();
        $driverImpl = $configuration->newDefaultAnnotationDriver(__DIR__.'/../../../../Model/Doctrine/ORM');
        $configuration->setMetadataDriverImpl($driverImpl);
        $configuration->setProxyDir(__DIR__.'/../../Model/Doctrine/ORM/Proxy');
        $configuration->setProxyNamespace('Proxy');
        $configuration->setAutoGenerateProxyClasses(true);

        $this->entityManager = EntityManager::create(array(
            'driver' => 'pdo_sqlite',
            'path'   => ':memory:',
        ), $configuration);

        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
    }

    protected function loadArticles($nb)
    {
        $articles = array();
        for ($i = 1; $i <= $nb; $i++) {
            $articles[] = $article = new Article();
            $article->setTitle($i);
            $this->entityManager->persist($article);
        }
        $this->entityManager->flush();

        return $articles;
    }
}
