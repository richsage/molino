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
        $this->query->filter('title', '==', 'foo');
        $this->assertSame('SELECT FROM Model\Doctrine\ORM\Article m WHERE m.title = ?1', $this->queryBuilder->getDQL());
        $this->assertSame(array(1 => 'foo'), $this->queryBuilder->getParameters());
    }

    public function testFilterNotEqual()
    {
        $this->query->filter('title', '!=', 'foo');
        $this->assertSame('SELECT FROM Model\Doctrine\ORM\Article m WHERE m.title <> ?1', $this->queryBuilder->getDQL());
        $this->assertSame(array(1 => 'foo'), $this->queryBuilder->getParameters());
    }

    public function testFilterIn()
    {
        $this->query->filter('title', 'in', array('foo', 'bar'));
        $this->assertSame('SELECT FROM Model\Doctrine\ORM\Article m WHERE m.title IN ?1', $this->queryBuilder->getDQL());
        $this->assertSame(array(1 => array('foo', 'bar')), $this->queryBuilder->getParameters());
    }

    public function testFilterNotIn()
    {
        $this->query->filter('title', 'not_in', array('foo', 'bar'));
        $this->assertSame('SELECT FROM Model\Doctrine\ORM\Article m WHERE m.title NOT IN ?1', $this->queryBuilder->getDQL());
        $this->assertSame(array(1 => array('foo', 'bar')), $this->queryBuilder->getParameters());
    }

    public function testFilterGreater()
    {
        $this->query->filter('age', '>', 20);
        $this->assertSame('SELECT FROM Model\Doctrine\ORM\Article m WHERE m.age > ?1', $this->queryBuilder->getDQL());
        $this->assertSame(array(1 => 20), $this->queryBuilder->getParameters());
    }

    public function testFilterLess()
    {
        $this->query->filter('age', '<', 20);
        $this->assertSame('SELECT FROM Model\Doctrine\ORM\Article m WHERE m.age < ?1', $this->queryBuilder->getDQL());
        $this->assertSame(array(1 => 20), $this->queryBuilder->getParameters());
    }

    public function testFilterGreaterEqual()
    {
        $this->query->filter('age', '>=', 20);
        $this->assertSame('SELECT FROM Model\Doctrine\ORM\Article m WHERE m.age >= ?1', $this->queryBuilder->getDQL());
        $this->assertSame(array(1 => 20), $this->queryBuilder->getParameters());
    }

    public function testFilterLessEqual()
    {
        $this->query->filter('age', '<=', 20);
        $this->assertSame('SELECT FROM Model\Doctrine\ORM\Article m WHERE m.age <= ?1', $this->queryBuilder->getDQL());
        $this->assertSame(array(1 => 20), $this->queryBuilder->getParameters());
    }

    public function testSeveral()
    {
        $this->query
            ->filter('title', '==', 'foo')
            ->filter('content', '!=', 'bar')
            ->filter('author', 'in', array(1, 2))
        ;
        $this->assertSame('SELECT FROM Model\Doctrine\ORM\Article m WHERE m.title = ?1 AND m.content <> ?2 AND m.author IN ?3', $this->queryBuilder->getDQL());
        $this->assertSame(array(1 => 'foo', 2 => 'bar', 3 => array(1, 2)), $this->queryBuilder->getParameters());
    }
}
