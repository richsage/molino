<?php

namespace Molino\Tests\Doctrine\ORM;

use Molino\Doctrine\ORM\Molino;
use Model\Doctrine\ORM\Article;

class MolinoTest extends TestCase
{
    private $molino;

    protected function setUp()
    {
        parent::setUp();

        $this->molino = new Molino($this->entityManager);
    }

    public function testGetEntityManager()
    {
        $this->assertSame($this->entityManager, $this->molino->getEntityManager());
    }

    public function testGetName()
    {
        $this->assertSame('doctrine_orm', $this->molino->getName());
    }

    public function testCreateModel()
    {
        $model = $this->molino->createModel('Model\Doctrine\ORM\Article');
        $this->assertInstanceOf('Model\Doctrine\ORM\Article', $model);
    }

    public function testSaveModel()
    {
        $article = new Article();
        $article->setTitle('foo');
        $this->molino->saveModel($article);
        $this->assertNotNull($article->getId());
    }

    public function testDeleteModel()
    {
        $article = new Article();
        $article->setTitle('foo');
        $this->entityManager->persist($article);
        $this->entityManager->flush();

        $id = $article->getId();
        $this->molino->deleteModel($article);
        $this->assertNull($this->entityManager->find('Model\Doctrine\ORM\Article', $id));
    }

    public function testCreateSelectQuery()
    {
        $query = $this->molino->createSelectQuery('Model\Doctrine\ORM\Article');
        $this->assertInstanceOf('Molino\Doctrine\ORM\SelectQuery', $query);
        $this->assertSame($this->entityManager, $query->getQueryBuilder()->getEntityManager());
        $this->assertSame(array('Model\Doctrine\ORM\Article'), $query->getQueryBuilder()->getRootEntities());
    }

    public function testCreateUpdateQuery()
    {
        $query = $this->molino->createUpdateQuery('Model\Doctrine\ORM\Article');
        $this->assertInstanceOf('Molino\Doctrine\ORM\UpdateQuery', $query);
        $this->assertSame($this->entityManager, $query->getQueryBuilder()->getEntityManager());
        $this->assertSame(array('Model\Doctrine\ORM\Article'), $query->getQueryBuilder()->getRootEntities());
    }

    public function testCreateDeleteQuery()
    {
        $query = $this->molino->createDeleteQuery('Model\Doctrine\ORM\Article');
        $this->assertInstanceOf('Molino\Doctrine\ORM\DeleteQuery', $query);
        $this->assertSame($this->entityManager, $query->getQueryBuilder()->getEntityManager());
        $this->assertSame(array('Model\Doctrine\ORM\Article'), $query->getQueryBuilder()->getRootEntities());
    }

    public function testFindOneById()
    {
        $modelClass = 'Model\Doctrine\ORM\Article';
        $id = 10;
        $article = new Article();

        $entityManager = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $entityManager
            ->expects($this->once())
            ->method('find')
            ->with($modelClass, $id)
            ->will($this->returnValue($article))
        ;

        $molino = new Molino($entityManager);
        $this->assertSame($article, $molino->findOneById($modelClass, $id));
    }
}
