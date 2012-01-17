<?php

namespace Molino\Tests\Mandango;

use Molino\Mandango\SelectQuery;

class SelectQueryTest extends TestCase
{
    private $mandangoQuery;
    private $query;

    protected function setUp()
    {
        parent::setUp();

        $this->mandangoQuery = $this->mandango->getRepository('Model\Mandango\Article')->createQuery();
        $this->query = new SelectQuery($this->mandangoQuery);
    }

    public function testGetMandangoQuery()
    {
        $this->assertSame($this->mandangoQuery, $this->query->getMandangoQuery());
    }

    public function testFiltering()
    {
        $this->query->filterEqual('foo', 'bar');
        $this->assertSame(array('foo' => 'bar'), $this->mandangoQuery->getCriteria());
    }

    public function testFields()
    {
        $this->assertSame($this->query, $this->query->fields(array('foo', 'bar')));
        $this->assertSame(array('foo' => 1, 'bar' => 1), $this->mandangoQuery->getFields());
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
        $this->assertSame(array('name' => 1), $this->mandangoQuery->getSort());
    }

    public function testSortDesc()
    {
        $this->assertSame($this->query, $this->query->sort('name', 'desc'));
        $this->assertSame(array('name' => -1), $this->mandangoQuery->getSort());
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
        $this->assertSame(array('_id' => 1), $this->mandangoQuery->getSort());
    }

    public function testLimit()
    {
        $this->assertSame($this->query, $this->query->limit(10));
        $this->assertSame(10, $this->mandangoQuery->getLimit());
    }

    public function testSkip()
    {
        $this->assertSame($this->query, $this->query->skip(20));
        $this->assertSame(20, $this->mandangoQuery->getSkip());
    }

    public function testAll()
    {
        $this->loadArticles(10);
        $this->assertSame($this->mandangoQuery->all(), $this->query->all());
    }

    public function testOne()
    {
        $this->loadArticles(10);
        $this->assertSame($this->mandangoQuery->one(), $this->query->one());
    }

    public function testCount()
    {
        $this->loadArticles(10);
        $this->assertSame(10, $this->query->count());
    }

    public function testGetIterator()
    {
        $this->assertEquals($this->mandangoQuery->getIterator(), $this->query->getIterator());
    }

    public function testCreatePagerfantaAdapter()
    {
        $adapter = $this->query->createPagerfantaAdapter();
        $this->assertInstanceOf('Pagerfanta\Adapter\MandangoAdapter', $adapter);
        $this->assertSame($this->mandangoQuery, $adapter->getQuery());
    }
}
