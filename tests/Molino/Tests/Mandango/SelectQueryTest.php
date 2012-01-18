<?php

namespace Molino\Tests\Mandango;

use Molino\Mandango\Molino;
use Molino\Mandango\SelectQuery;

class SelectQueryTest extends TestCase
{
    private $molino;
    private $modelClass;
    private $query;

    protected function setUp()
    {
        parent::setUp();

        $this->molino = new Molino($this->mandango);
        $this->modelClass = 'Model\Mandango\Article';
        $this->query = new SelectQuery($this->molino, $this->modelClass);
    }

    public function testGetMandangoQuery()
    {
        $mandangoQuery = $this->query->getMandangoQuery();
        $this->assertInstanceOf('Model\Mandango\ArticleQuery', $mandangoQuery);
        $this->assertSame($mandangoQuery, $this->query->getMandangoQuery());
    }

    public function testFiltering()
    {
        $this->query->filterEqual('foo', 'bar');
        $this->assertSame(array('foo' => 'bar'), $this->query->getMandangoQuery()->getCriteria());
    }

    public function testFields()
    {
        $this->assertSame($this->query, $this->query->fields(array('foo', 'bar')));
        $this->assertSame(array('foo' => 1, 'bar' => 1), $this->query->getMandangoQuery()->getFields());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFieldsNotString()
    {
        $this->query->fields(array('foo', 12));
    }

    public function testSortAsc()
    {
        $this->assertSame($this->query, $this->query->sort('name', 'asc'));
        $this->assertSame(array('name' => 1), $this->query->getMandangoQuery()->getSort());
    }

    public function testSortDesc()
    {
        $this->assertSame($this->query, $this->query->sort('name', 'desc'));
        $this->assertSame(array('name' => -1), $this->query->getMandangoQuery()->getSort());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSortOrderNotValid()
    {
        $this->query->sort('name', 'no');
    }

    public function testSortIdUnderscore()
    {
        $this->query->sort('id');
        $this->assertSame(array('_id' => 1), $this->query->getMandangoQuery()->getSort());
    }

    public function testLimit()
    {
        $this->assertSame($this->query, $this->query->limit(10));
        $this->assertSame(10, $this->query->getMandangoQuery()->getLimit());
    }

    public function testSkip()
    {
        $this->assertSame($this->query, $this->query->skip(20));
        $this->assertSame(20, $this->query->getMandangoQuery()->getSkip());
    }

    public function testAll()
    {
        $this->loadArticles(10);
        $this->assertSame($this->query->getMandangoQuery()->all(), $this->query->all());
    }

    public function testOne()
    {
        $this->loadArticles(10);
        $this->assertSame($this->query->getMandangoQuery()->one(), $this->query->one());
    }

    public function testCount()
    {
        $this->loadArticles(10);
        $this->assertSame(10, $this->query->count());
    }

    public function testGetIterator()
    {
        $this->assertEquals($this->query->getMandangoQuery()->getIterator(), $this->query->getIterator());
    }

    public function testCreatePagerfantaAdapter()
    {
        $adapter = $this->query->createPagerfantaAdapter();
        $this->assertInstanceOf('Pagerfanta\Adapter\MandangoAdapter', $adapter);
        $this->assertSame($this->query->getMandangoQuery(), $adapter->getQuery());
    }
}
