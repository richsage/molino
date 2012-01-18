<?php

namespace Molino\Tests\Mandango;

use Molino\Mandango\Molino;
use Molino\Mandango\DeleteQuery;

class DeleteQueryTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $modelClass = 'Model\Article';

        $repository = $this->getMockBuilder('Mandango\Repository')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $repository
            ->expects($this->once())
            ->method('remove')
            ->with(array('name' => 'foo'))
        ;

        $mandango = $this->getMockBuilder('Mandango\Mandango')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $mandango
            ->expects($this->any())
            ->method('getRepository')
            ->with($modelClass)
            ->will($this->returnValue($repository))
        ;

        $molino = new Molino($mandango);
        $query = new DeleteQuery($molino, $modelClass);
        $query->filterEqual('name', 'foo');
        $query->execute();
    }
}
