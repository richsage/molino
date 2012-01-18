<?php

namespace Molino\Tests\Event;

use Molino\Event\ModelEvent;

class ModelEventTest extends \PHPUnit_Framework_TestCase
{
    private $molino;
    private $model;
    private $event;

    protected function setUp()
    {
        $this->molino = $this->getMock('Molino\MolinoInterface');
        $this->model = new \ArrayObject();
        $this->event = new ModelEvent($this->molino, $this->model);
    }

    public function testConstructorGetModel()
    {
        $this->assertSame($this->molino, $this->event->getMolino());
        $this->assertSame($this->model, $this->event->getModel());
    }

    public function testSetGetModel()
    {
        $model = new \ArrayObject();
        $this->event->setModel($model);
        $this->assertSame($model, $this->event->getModel());
    }
}
