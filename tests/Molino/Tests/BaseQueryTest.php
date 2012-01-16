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

    protected function parseField($field)
    {
        return $field.'_parsed';
    }

    protected function filterEqual($field, $value)
    {
        $this->filters['equal'] = array($field, $value);
    }

    protected function filterNotEqual($field, $value)
    {
        $this->filters['not_equal'] = array($field, $value);
    }

    protected function filterIn($field, array $values)
    {
        $this->filters['in'] = array($field, $values);
    }

    protected function filterNotIn($field, array $values)
    {
        $this->filters['not_in'] = array($field, $values);
    }

    protected function filterGreater($field, $value)
    {
        $this->filters['greater'] = array($field, $value);
    }

    protected function filterLess($field, $value)
    {
        $this->filters['less'] = array($field, $value);
    }

    protected function filterGreaterEqual($field, $value)
    {
        $this->filters['greater_equal'] = array($field, $value);
    }

    protected function filterLessEqual($field, $value)
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
        $this->assertSame(array('equal' => array('name_parsed', 'Pablo')), $this->query->getFilters());
    }

    public function testFilterNotEqual()
    {
        $this->assertSame($this->query, $this->query->filter('name', '!=', 'Pablo'));
        $this->assertSame(array('not_equal' => array('name_parsed', 'Pablo')), $this->query->getFilters());
    }

    public function testFilterIn()
    {
        $this->assertSame($this->query, $this->query->filter('name', 'in', array('Pablo', 'Pepe')));
        $this->assertSame(array('in' => array('name_parsed', array('Pablo', 'Pepe'))), $this->query->getFilters());
    }

    public function testFilterNotIn()
    {
        $this->assertSame($this->query, $this->query->filter('name', 'not_in', array('Pablo', 'Pepe')));
        $this->assertSame(array('not_in' => array('name_parsed', array('Pablo', 'Pepe'))), $this->query->getFilters());
    }

    public function testFilterGreater()
    {
        $this->assertSame($this->query, $this->query->filter('age', '>', 20));
        $this->assertSame(array('greater' => array('age_parsed', 20)), $this->query->getFilters());
    }

    public function testFilterLess()
    {
        $this->assertSame($this->query, $this->query->filter('age', '<', 20));
        $this->assertSame(array('less' => array('age_parsed', 20)), $this->query->getFilters());
    }

    public function testFilterGreaterEqual()
    {
        $this->assertSame($this->query, $this->query->filter('age', '>=', 20));
        $this->assertSame(array('greater_equal' => array('age_parsed', 20)), $this->query->getFilters());
    }

    public function testFilterLessEqual()
    {
        $this->assertSame($this->query, $this->query->filter('age', '<=', 20));
        $this->assertSame(array('less_equal' => array('age_parsed', 20)), $this->query->getFilters());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFilterComparisonNotSupported()
    {
        $this->query->filter('name', 'no', 'Pablo');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFilterInValueNotArray()
    {
        $this->query->filter('name', 'in', 'string');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFilterNotInValueNotArray()
    {
        $this->query->filter('name', 'not_in', 'string');
    }
}
