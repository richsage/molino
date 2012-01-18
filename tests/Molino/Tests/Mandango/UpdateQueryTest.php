<?php

namespace Molino\Tests\Mandango;

use Molino\Mandango\Molino;
use Molino\Mandango\UpdateQuery;

class UpdateQueryTest extends \PHPUnit_Framework_TestCase
{
    private $modelClass;
    private $mandango;
    private $molino;
    private $query;

    protected function setUp()
    {
        $this->modelClass = 'Model\Article';
        $this->mandango = $this->getMockBuilder('Mandango\Mandango')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this->molino = new Molino($this->mandango);
        $this->query = new UpdateQuery($this->molino, $this->modelClass);
    }

    public function testConstructorGetNewObject()
    {
        $this->assertSame($this->molino, $this->query->getMolino());
        $this->assertSame($this->modelClass, $this->query->getModelClass());
        $this->assertSame(array(), $this->query->getNewObject());
    }

    public function testSet()
    {
        $this->assertSame($this->query, $this->query->set('name', 'foo'));
        $this->assertSame(array('$set' => array('name' => 'foo')), $this->query->getNewObject());
    }

    public function testInc()
    {
        $this->assertSame($this->query, $this->query->inc('age', 1));
        $this->assertSame(array('$inc' => array('age' => 1)), $this->query->getNewObject());
    }

    public function testExecute()
    {
        $repository = $this->getMockBuilder('Mandango\Repository')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $repository
            ->expects($this->once())
            ->method('update')
            ->with(array('name' => 'foo'), array('$set' => array('name' => 'bar')))
        ;

        $this->mandango
            ->expects($this->any())
            ->method('getRepository')
            ->with($this->modelClass)
            ->will($this->returnValue($repository))
        ;

        $this->query->filterEqual('name', 'foo');
        $this->query->set('name', 'bar');
        $this->query->execute();
    }
}
