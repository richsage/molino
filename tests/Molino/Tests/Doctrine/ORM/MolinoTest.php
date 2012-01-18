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

    public function testCreate()
    {
        $model = $this->molino->create('Model\Doctrine\ORM\Article');
        $this->assertInstanceOf('Model\Doctrine\ORM\Article', $model);
    }

    public function testSave()
    {
        $article = new Article();
        $article->setTitle('foo');
        $this->molino->save($article);
        $this->assertNotNull($article->getId());
    }

    public function testRefresh()
    {
        $article = new Article();
        $article->setTitle('foo');
        $this->entityManager->persist($article);
        $this->entityManager->flush();

        $this->entityManager->getRepository('Model\Doctrine\ORM\Article')
            ->createQueryBuilder('a')
            ->update()
            ->set('a.title', '?1')
            ->setParameter(1, 'bar')
            ->getQuery()
            ->execute()
        ;

        $this->molino->refresh($article);
        $this->assertSame('bar', $article->getTitle());
    }

    public function testDelete()
    {
        $article = new Article();
        $article->setTitle('foo');
        $this->entityManager->persist($article);
        $this->entityManager->flush();

        $id = $article->getId();
        $this->molino->delete($article);
        $this->assertNull($this->entityManager->find('Model\Doctrine\ORM\Article', $id));
    }

    public function testCreateSelectQuery()
    {
        $query = $this->molino->createSelectQuery('Model\Doctrine\ORM\Article');
        $this->assertInstanceOf('Molino\Doctrine\ORM\SelectQuery', $query);
        $this->assertSame($this->molino, $query->getMolino());
        $this->assertSame('Model\Doctrine\ORM\Article', $query->getModelClass());
    }

    public function testCreateUpdateQuery()
    {
        $query = $this->molino->createUpdateQuery('Model\Doctrine\ORM\Article');
        $this->assertInstanceOf('Molino\Doctrine\ORM\UpdateQuery', $query);
        $this->assertSame($this->molino, $query->getMolino());
        $this->assertSame('Model\Doctrine\ORM\Article', $query->getModelClass());
    }

    public function testCreateDeleteQuery()
    {
        $query = $this->molino->createDeleteQuery('Model\Doctrine\ORM\Article');
        $this->assertInstanceOf('Molino\Doctrine\ORM\DeleteQuery', $query);
        $this->assertSame($this->molino, $query->getMolino());
        $this->assertSame('Model\Doctrine\ORM\Article', $query->getModelClass());
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
