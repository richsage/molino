<?php

namespace Molino\Tests\Doctrine\ORM;

use Molino\Doctrine\ORM\BaseQuery as OriginalBaseQuery;

class BaseQuery extends OriginalBaseQuery
{
}

class BaseQueryTest extends TestCase
{
    private $queryBuilder;
    private $query;

    protected function setUp()
    {
        parent::setUp();

        $this->queryBuilder = $this->entityManager->createQueryBuilder()->from('Model\Doctrine\ORM\Article', 'm');
        $this->query = new BaseQuery($this->queryBuilder);
    }

    public function testGetQueryBuilder()
    {
        $this->assertSame($this->queryBuilder, $this->query->getQueryBuilder());
    }

    public function testFilterEqual()
    {
        $this->assertSame($this->query, $this->query->filterEqual('title', 'foo'));
        $this->assertSame('SELECT FROM Model\Doctrine\ORM\Article m WHERE m.title = ?1', $this->queryBuilder->getDQL());
        $this->assertSame(array(1 => 'foo'), $this->queryBuilder->getParameters());
    }

    public function testFilterNotEqual()
    {
        $this->assertSame($this->query, $this->query->filterNotEqual('title', 'foo'));
        $this->assertSame('SELECT FROM Model\Doctrine\ORM\Article m WHERE m.title <> ?1', $this->queryBuilder->getDQL());
        $this->assertSame(array(1 => 'foo'), $this->queryBuilder->getParameters());
    }

    public function testFilterIn()
    {
        $this->assertSame($this->query, $this->query->filterIn('title', array('foo', 'bar')));
        $this->assertSame('SELECT FROM Model\Doctrine\ORM\Article m WHERE m.title IN ?1', $this->queryBuilder->getDQL());
        $this->assertSame(array(1 => array('foo', 'bar')), $this->queryBuilder->getParameters());
    }

    public function testFilterNotIn()
    {
        $this->assertSame($this->query, $this->query->filterNotIn('title', array('foo', 'bar')));
        $this->assertSame('SELECT FROM Model\Doctrine\ORM\Article m WHERE m.title NOT IN ?1', $this->queryBuilder->getDQL());
        $this->assertSame(array(1 => array('foo', 'bar')), $this->queryBuilder->getParameters());
    }

    public function testFilterGreater()
    {
        $this->assertSame($this->query, $this->query->filterGreater('age', 20));
        $this->assertSame('SELECT FROM Model\Doctrine\ORM\Article m WHERE m.age > ?1', $this->queryBuilder->getDQL());
        $this->assertSame(array(1 => 20), $this->queryBuilder->getParameters());
    }

    public function testFilterLess()
    {
        $this->assertSame($this->query, $this->query->filterLess('age', 20));
        $this->assertSame('SELECT FROM Model\Doctrine\ORM\Article m WHERE m.age < ?1', $this->queryBuilder->getDQL());
        $this->assertSame(array(1 => 20), $this->queryBuilder->getParameters());
    }

    public function testFilterGreaterEqual()
    {
        $this->assertSame($this->query, $this->query->filterGreaterEqual('age', 20));
        $this->assertSame('SELECT FROM Model\Doctrine\ORM\Article m WHERE m.age >= ?1', $this->queryBuilder->getDQL());
        $this->assertSame(array(1 => 20), $this->queryBuilder->getParameters());
    }

    public function testFilterLessEqual()
    {
        $this->assertSame($this->query, $this->query->filterLessEqual('age', 20));
        $this->assertSame('SELECT FROM Model\Doctrine\ORM\Article m WHERE m.age <= ?1', $this->queryBuilder->getDQL());
        $this->assertSame(array(1 => 20), $this->queryBuilder->getParameters());
    }

    public function testSeveral()
    {
        $this->query
            ->filterEqual('title', 'foo')
            ->filterNotEqual('content', 'bar')
            ->filterIn('author', array(1, 2))
        ;
        $this->assertSame('SELECT FROM Model\Doctrine\ORM\Article m WHERE m.title = ?1 AND m.content <> ?2 AND m.author IN ?3', $this->queryBuilder->getDQL());
        $this->assertSame(array(1 => 'foo', 2 => 'bar', 3 => array(1, 2)), $this->queryBuilder->getParameters());
    }
}
