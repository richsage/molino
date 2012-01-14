<?php

namespace Molino\Tests\Mandango;

use Molino\Mandango\UpdateQuery;

class UpdateQueryTest extends TestCase
{
    private $repository;
    private $query;

    protected function setUp()
    {
        parent::setUp();

        $this->repository = $this->mandango->getRepository('Model\Mandango\Article');
        $this->query = new UpdateQuery($this->repository);
    }

    public function testGetRepository()
    {
        $this->assertSame($this->repository, $this->query->getRepository());
    }

    public function testGetNewObject()
    {
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

        $query = new UpdateQuery($repository);
        $query->filterEqual('name', 'foo');
        $query->set('name', 'bar');
        $query->execute();
    }
}
