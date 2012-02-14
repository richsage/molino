<?php

namespace Molino\Tests\Mandango;

use Molino\Mandango\Molino;
use Molino\Mandango\BaseQuery as OriginalBaseQuery;

class BaseQuery extends OriginalBaseQuery
{
}

class BaseQueryTest extends \PHPUnit_Framework_TestCase
{
    private $molino;
    private $modelClass;
    private $query;

    protected function setUp()
    {
        $this->molino = $this->getMockBuilder('Molino\Mandango\Molino')->disableOriginalConstructor()->getMock();
        $this->modelClass = 'Model\Article';
        $this->query = new BaseQuery($this->molino, $this->modelClass);
    }

    public function testConstructorGetCriteria()
    {
        $this->assertSame($this->molino, $this->query->getMolino());
        $this->assertSame($this->modelClass, $this->query->getModelClass());
        $this->assertSame(array(), $this->query->getCriteria());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetMolinoNotMandango()
    {
        $molino = $this->getMock('Molino\MolinoInterface');
        $this->query->setMolino($molino);
    }

    public function testFilterEqual()
    {
        $this->assertSame($this->query, $this->query->filterEqual('name', 'foo'));
        $this->assertSame(array('name' => 'foo'), $this->query->getCriteria());
    }

    public function testFilterEqualIdUnderscore()
    {
        $this->query->filterEqual('id', 1);
        $this->assertSame(array('_id' => 1), $this->query->getCriteria());
    }

    public function testFilterNotEqual()
    {
        $this->assertSame($this->query, $this->query->filterNotEqual('name', 'foo'));
        $this->assertSame(array('name' => array('$ne' => 'foo')), $this->query->getCriteria());
    }

    public function testFilterNotEqualIdUnderscore()
    {
        $this->query->filterNotEqual('id', 1);
        $this->assertSame(array('_id' => array('$ne' => 1)), $this->query->getCriteria());
    }

    /**
     * @dataProvider filterLikeProvider
     */
    public function testFilterLike($value, $pattern)
    {
        $this->assertSame($this->query, $this->query->filterLike('name', $value));
        $this->assertEquals(array('name' => new \MongoRegex($pattern)), $this->query->getCriteria());
    }

    /**
     * @dataProvider filterLikeProvider
     */
    public function testFilterNotLike($value, $pattern)
    {
        $this->assertSame($this->query, $this->query->filterNotLike('name', $value));
        $this->assertEquals(array('name' => array('$not' => new \MongoRegex($pattern))), $this->query->getCriteria());
    }

    public function filterLikeProvider()
    {
        return array(
            array('foo', '/^foo$/'),
            array('*foo', '/.*foo$/'),
            array('foo*', '/^foo.*/'),
            array('*foo*', '/.*foo.*/'),
            array('f*oo', '/^f.*oo$/'),
        );
    }

    public function testFilterIn()
    {
        $this->assertSame($this->query, $this->query->filterIn('name', array('foo', 'bar')));
        $this->assertSame(array('name' => array('$in' => array('foo', 'bar'))), $this->query->getCriteria());
    }

    public function testFilterInIdUnderscore()
    {
        $this->query->filterIn('id', array(1, 2));
        $this->assertSame(array('_id' => array('$in' => array(1, 2))), $this->query->getCriteria());
    }

    public function testFilterNotIn()
    {
        $this->assertSame($this->query, $this->query->filterNotIn('name', array('foo', 'bar')));
        $this->assertSame(array('name' => array('$nin' => array('foo', 'bar'))), $this->query->getCriteria());
    }

    public function testFilterNotInIdUnderscore()
    {
        $this->query->filterNotIn('id', array(1, 2));
        $this->assertSame(array('_id' => array('$nin' => array(1, 2))), $this->query->getCriteria());
    }

    public function testGreater()
    {
        $this->assertSame($this->query, $this->query->filterGreater('age', 20));
        $this->assertSame(array('age' => array('$gt' => 20)), $this->query->getCriteria());
    }

    public function testFilterGreaterIdUnderscore()
    {
        $this->query->filterGreater('id', 10);
        $this->assertSame(array('_id' => array('$gt' => 10)), $this->query->getCriteria());
    }

    public function testLess()
    {
        $this->assertSame($this->query, $this->query->filterLess('age', 20));
        $this->assertSame(array('age' => array('$lt' => 20)), $this->query->getCriteria());
    }

    public function testFilterLessIdUnderscore()
    {
        $this->query->filterLess('id', 10);
        $this->assertSame(array('_id' => array('$lt' => 10)), $this->query->getCriteria());
    }

    public function testGreaterEqual()
    {
        $this->assertSame($this->query, $this->query->filterGreaterEqual('age', 20));
        $this->assertSame(array('age' => array('$gte' => 20)), $this->query->getCriteria());
    }

    public function testFilterGreaterEqualIdUnderscore()
    {
        $this->query->filterGreaterEqual('id', 10);
        $this->assertSame(array('_id' => array('$gte' => 10)), $this->query->getCriteria());
    }

    public function testLessEqual()
    {
        $this->assertSame($this->query, $this->query->filterLessEqual('age', 20));
        $this->assertSame(array('age' => array('$lte' => 20)), $this->query->getCriteria());
    }

    public function testFilterLessEqualIdUnderscore()
    {
        $this->query->filterLessEqual('id', 10);
        $this->assertSame(array('_id' => array('$lte' => 10)), $this->query->getCriteria());
    }
}
