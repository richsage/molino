<?php

namespace Molino\Tests\Doctrine\ORM;

use Molino\Doctrine\ORM\UpdateQuery;
use Doctrine\ORM\QueryBuilder;

class UpdateQueryTest extends TestCase
{
    private $queryBuilder;
    private $query;

    protected function setUp()
    {
        parent::setUp();

        $this->queryBuilder = $this->entityManager->createQueryBuilder()->from('Model\Doctrine\ORM\Article', 'm');
        $this->query = new UpdateQuery($this->queryBuilder);
    }

    public function testConstructorUpdate()
    {
        $this->assertSame(QueryBuilder::UPDATE, $this->queryBuilder->getType());
    }

    public function testSet()
    {
        $this->assertSame($this->query, $this->query->set('title', 'foo'));
        $this->assertSame('UPDATE Model\Doctrine\ORM\Article m SET m.title = ?1', $this->queryBuilder->getDQL());
        $this->assertSame(array(1 => 'foo'), $this->queryBuilder->getParameters());
    }

    public function testInc()
    {
        $this->assertSame($this->query, $this->query->inc('age', 1));
        $this->assertSame('UPDATE Model\Doctrine\ORM\Article m SET m.age = m.age + 1', $this->queryBuilder->getDQL());
    }

    public function testExecute()
    {
        $articles = $this->loadArticles(10);

        $this->query
            ->filter('id', '==', $articles[0]->getId())
            ->set('title', 'foo')
            ->execute()
        ;
        $this->entityManager->refresh($articles[0]);
        $this->assertSame('foo', $articles[0]->getTitle());
    }
}
