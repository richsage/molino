<?php

namespace Molino\Tests\Event;

use Molino\Event\Event;

class EventTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorGetMolino()
    {
        $molino = $this->getMock('Molino\MolinoInterface');
        $event = new Event($molino);
        $this->assertSame($molino, $event->getMolino());
    }
}
