<?php

namespace Molino\Tests\Mandango;

use Molino\Mandango\Molino;
use Molino\Mandango\BaseQuery as OriginalBaseQuery;

class BaseQuery extends OriginalBaseQuery
{
}

class BaseQueryTest extends \PHPUnit_Framework_TestCase
{
    private $query;

    protected function setUp()
    {
        $this->query = new BaseQuery();
    }

    public function testGetCriteria()
    {
        $this->assertSame(array(), $this->query->getCriteria());
    }

    public function testFilterEqual()
    {
        $this->query->filter('name', '==', 'foo');
        $this->assertSame(array('name' => 'foo'), $this->query->getCriteria());
    }

    public function testFilterNotEqual()
    {
        $this->query->filter('name', '!=', 'foo');
        $this->assertSame(array('name' => array('$ne' => 'foo')), $this->query->getCriteria());
    }

    public function testFilterIn()
    {
        $this->query->filter('name', 'in', array('foo', 'bar'));
        $this->assertSame(array('name' => array('$in' => array('foo', 'bar'))), $this->query->getCriteria());
    }

    public function testFilterNotIn()
    {
        $this->query->filter('name', 'not_in', array('foo', 'bar'));
        $this->assertSame(array('name' => array('$nin' => array('foo', 'bar'))), $this->query->getCriteria());
    }

    public function testGreater()
    {
        $this->query->filter('age', '>', 20);
        $this->assertSame(array('age' => array('$gt' => 20)), $this->query->getCriteria());
    }

    public function testLess()
    {
        $this->query->filter('age', '<', 20);
        $this->assertSame(array('age' => array('$lt' => 20)), $this->query->getCriteria());
    }

    public function testGreaterEqual()
    {
        $this->query->filter('age', '>=', 20);
        $this->assertSame(array('age' => array('$gte' => 20)), $this->query->getCriteria());
    }

    public function testLessEqual()
    {
        $this->query->filter('age', '<=', 20);
        $this->assertSame(array('age' => array('$lte' => 20)), $this->query->getCriteria());
    }
}
