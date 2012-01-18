<?php

namespace Molino\Tests\Doctrine\ORM;

use Molino\Doctrine\ORM\Molino;
use Molino\Doctrine\ORM\UpdateQuery;
use Doctrine\ORM\QueryBuilder;

class UpdateQueryTest extends TestCase
{
    private $query;

    protected function setUp()
    {
        parent::setUp();

        $this->molino = new Molino($this->entityManager);
        $this->modelClass = 'Model\Doctrine\ORM\Article';
        $this->query = new UpdateQuery($this->molino, $this->modelClass);
    }

    public function testConfigureQueryBuilder()
    {
        $this->assertSame(QueryBuilder::UPDATE, $this->query->getQueryBuilder()->getType());
    }

    public function testSet()
    {
        $this->assertSame($this->query, $this->query->set('title', 'foo'));
        $this->assertSame('UPDATE Model\Doctrine\ORM\Article m SET m.title = ?1', $this->query->getQueryBuilder()->getDQL());
        $this->assertSame(array(1 => 'foo'), $this->query->getQueryBuilder()->getParameters());
    }

    public function testInc()
    {
        $this->assertSame($this->query, $this->query->inc('age', 1));
        $this->assertSame('UPDATE Model\Doctrine\ORM\Article m SET m.age = m.age + 1', $this->query->getQueryBuilder()->getDQL());
    }

    public function testExecute()
    {
        $articles = $this->loadArticles(10);

        $this->query
            ->filterEqual('id', $articles[0]->getId())
            ->set('title', 'foo')
            ->execute()
        ;
        $this->entityManager->refresh($articles[0]);
        $this->assertSame('foo', $articles[0]->getTitle());
    }
}
