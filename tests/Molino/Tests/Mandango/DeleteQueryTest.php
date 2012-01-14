<?php

namespace Molino\Tests\Mandango;

use Molino\Mandango\DeleteQuery;

class DeleteQueryTest extends TestCase
{
    private $repository;
    private $query;

    protected function setUp()
    {
        parent::setUp();

        $this->repository = $this->mandango->getRepository('Model\Mandango\Article');
        $this->query = new DeleteQuery($this->repository);
    }

    public function testGetRepository()
    {
        $this->assertSame($this->repository, $this->query->getRepository());
    }

    public function testExecute()
    {
        $repository = $this->getMockBuilder('Mandango\Repository')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $repository
            ->expects($this->once())
            ->method('remove')
            ->with(array('name' => 'foo'))
        ;

        $query = new DeleteQuery($repository);
        $query->filterEqual('name', 'foo');
        $query->execute();
    }
}
