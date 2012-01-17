<?php

namespace Molino\Tests;

use Molino\BaseQuery as OriginalBaseQuery;

class BaseQuery extends OriginalBaseQuery
{
    private $filters;

    public function __construct()
    {
        $this->fitlers = array();
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function filterEqual($field, $value)
    {
        $this->filters['equal'] = array($field, $value);
    }

    public function filterNotEqual($field, $value)
    {
        $this->filters['not_equal'] = array($field, $value);
    }

    public function filterIn($field, array $values)
    {
        $this->filters['in'] = array($field, $values);
    }

    public function filterNotIn($field, array $values)
    {
        $this->filters['not_in'] = array($field, $values);
    }

    public function filterGreater($field, $value)
    {
        $this->filters['greater'] = array($field, $value);
    }

    public function filterLess($field, $value)
    {
        $this->filters['less'] = array($field, $value);
    }

    public function filterGreaterEqual($field, $value)
    {
        $this->filters['greater_equal'] = array($field, $value);
    }

    public function filterLessEqual($field, $value)
    {
        $this->filters['less_equal'] = array($field, $value);
    }
}

class BaseQueryTest extends \PHPUnit_Framework_TestCase
{
    private $query;

    protected function setUp()
    {
        $this->query = new BaseQuery();
    }

    public function testFilterEqual()
    {
        $this->assertSame($this->query, $this->query->filter('name', '==', 'Pablo'));
        $this->assertSame(array('equal' => array('name', 'Pablo')), $this->query->getFilters());
    }

    public function testFilterNotEqual()
    {
        $this->assertSame($this->query, $this->query->filter('name', '!=', 'Pablo'));
        $this->assertSame(array('not_equal' => array('name', 'Pablo')), $this->query->getFilters());
    }

    public function testFilterIn()
    {
        $this->assertSame($this->query, $this->query->filter('name', 'in', array('Pablo', 'Pepe')));
        $this->assertSame(array('in' => array('name', array('Pablo', 'Pepe'))), $this->query->getFilters());
    }

    public function testFilterNotIn()
    {
        $this->assertSame($this->query, $this->query->filter('name', 'not_in', array('Pablo', 'Pepe')));
        $this->assertSame(array('not_in' => array('name', array('Pablo', 'Pepe'))), $this->query->getFilters());
    }

    public function testFilterGreater()
    {
        $this->assertSame($this->query, $this->query->filter('age', '>', 20));
        $this->assertSame(array('greater' => array('age', 20)), $this->query->getFilters());
    }

    public function testFilterLess()
    {
        $this->assertSame($this->query, $this->query->filter('age', '<', 20));
        $this->assertSame(array('less' => array('age', 20)), $this->query->getFilters());
    }

    public function testFilterGreaterEqual()
    {
        $this->assertSame($this->query, $this->query->filter('age', '>=', 20));
        $this->assertSame(array('greater_equal' => array('age', 20)), $this->query->getFilters());
    }

    public function testFilterLessEqual()
    {
        $this->assertSame($this->query, $this->query->filter('age', '<=', 20));
        $this->assertSame(array('less_equal' => array('age', 20)), $this->query->getFilters());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFilterComparisonNotSupported()
    {
        $this->query->filter('name', 'no', 'Pablo');
    }
}
