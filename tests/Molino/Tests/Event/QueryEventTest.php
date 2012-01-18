<?php

namespace Molino\Tests\Event;

use Molino\Event\QueryEvent;

class QueryEventTest extends \PHPUnit_Framework_TestCase
{
    private $molino;
    private $query;
    private $event;

    protected function setUp()
    {
        $this->molino = $this->getMock('Molino\MolinoInterface');
        $this->query = $this->getMock('Molino\QueryInterface');
        $this->event = new QueryEvent($this->molino, $this->query);
    }

    public function testConstructorGetQuery()
    {
        $this->assertSame($this->molino, $this->event->getMolino());
        $this->assertSame($this->query, $this->event->getQuery());
    }

    public function testSetGetQuery()
    {
        $query = $this->getMock('Molino\QueryInterface');
        $this->event->setQuery($query);
        $this->assertSame($query, $this->event->getQuery());
    }
}
