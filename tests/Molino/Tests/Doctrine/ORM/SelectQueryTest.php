<?php

namespace Molino\Tests\Doctrine\ORM;

use Molino\Doctrine\ORM\SelectQuery;
use Doctrine\ORM\QueryBuilder;

class SelectQueryTest extends TestCase
{
    private $queryBuilder;
    private $query;

    protected function setUp()
    {
        parent::setUp();

        $this->queryBuilder = $this->entityManager->createQueryBuilder()->from('Model\Doctrine\ORM\Article', 'm');
        $this->query = new SelectQuery($this->queryBuilder);
    }

    public function testConstructorUpdate()
    {
        $this->assertSame(QueryBuilder::SELECT, $this->queryBuilder->getType());
    }

    public function testFields()
    {
        $this->assertSame($this->query, $this->query->fields(array('foo', 'bar')));
        $this->assertSame('SELECT m.foo, m.bar FROM Model\Doctrine\ORM\Article m', $this->queryBuilder->getDQL());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFieldsNotString()
    {
        $this->query->fields(array('foo', 12));
    }

    public function testSort()
    {
        $this->assertSame($this->query, $this->query->sort('title'));
        $this->assertSame('SELECT m FROM Model\Doctrine\ORM\Article m ORDER BY m.title ASC', $this->queryBuilder->getDQL());
    }

    public function testSortAsc()
    {
        $this->assertSame($this->query, $this->query->sort('title', 'asc'));
        $this->assertSame('SELECT m FROM Model\Doctrine\ORM\Article m ORDER BY m.title ASC', $this->queryBuilder->getDQL());
    }

    public function testSortDesc()
    {
        $this->assertSame($this->query, $this->query->sort('title', 'desc'));
        $this->assertSame('SELECT m FROM Model\Doctrine\ORM\Article m ORDER BY m.title DESC', $this->queryBuilder->getDQL());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSortOrderNotValid()
    {
        $this->query->sort('name', 'no');
    }

    public function testLimit()
    {
        $this->assertSame($this->query, $this->query->limit(10));
        $this->assertSame(10, $this->queryBuilder->getMaxResults());
    }

    public function testSkip()
    {
        $this->assertSame($this->query, $this->query->skip(20));
        $this->assertSame(20, $this->queryBuilder->getFirstResult());
    }

    public function testAll()
    {
        // cannot mock Doctrine\ORM\Query because it's declared as final

        $articles = $this->loadArticles(10);
        $this->assertSame($articles, $this->query->all());
    }

    public function testOne()
    {
        $articles = $this->loadArticles(10);
        $this->assertSame($articles[0], $this->query->one());
    }

    public function testOneNull()
    {
        $this->assertNull($this->query->one());
    }

    public function testCount()
    {
        $this->loadArticles(10);
        $this->assertSame(10, $this->query->count());
    }

    public function testGetIterator()
    {
        $articles = $this->loadArticles(10);
        $iterator = $this->query->getIterator();
        $this->assertInstanceOf('Traversable', $iterator);
        $this->assertSame($articles, iterator_to_array($iterator));
    }
}
