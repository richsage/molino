<?php

namespace Molino\Tests;

use Molino\EventMolino;
use Molino\Event\Events;
use Symfony\Component\EventDispatcher\EventDispatcher;

class EventMolinoTest extends \PHPUnit_Framework_TestCase
{
    private $molino;
    private $eventDispatcher;
    private $eventMolino;

    protected function setUp()
    {
        $this->molino = $this->getMock('Molino\MolinoInterface');
        $this->eventDispatcher = new EventDispatcher();
        $this->eventMolino = new EventMolino($this->molino, $this->eventDispatcher);
    }

    public function testConstructorGetMolinoGetEventDispatcher()
    {
        $this->assertSame($this->molino, $this->eventMolino->getMolino());
        $this->assertSame($this->eventDispatcher, $this->eventMolino->getEventDispatcher());
    }

    public function testGetName()
    {
        $this->molino
            ->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('foo'))
        ;

        $this->assertSame('foo', $this->eventMolino->getName());
    }

    public function testCreate()
    {
        $modelClass = 'Model\Article';
        $model = new \ArrayObject();

        $this->molino
            ->expects($this->once())
            ->method('create')
            ->with($modelClass)
            ->will($this->returnValue($model))
        ;

        $this->assertSame($model, $this->eventMolino->create($modelClass));
    }

    public function testCreateCreateEvent()
    {
        $model = new \ArrayObject();
        $modelModified = new \ArrayObject();

        $this->molino
            ->expects($this->any())
            ->method('create')
            ->will($this->returnValue($model))
        ;

        $testCase = $this;
        $dispatched = new \ArrayObject(array('dispatched' => false));
        $molino = $this->molino;
        $this->eventDispatcher->addListener(Events::CREATE,
            function ($event) use ($testCase, $dispatched, $molino, $model, $modelModified) {
                $dispatched['dispatched'] = true;
                $testCase->assertInstanceOf('Molino\Event\ModelEvent', $event);
                $testCase->assertSame($molino, $event->getMolino());
                $testCase->assertSame($model, $event->getModel());
                $event->setModel($modelModified);
            }
        );

        $this->assertSame($modelModified, $this->eventMolino->create('Model\Article'));
        $this->assertTrue($dispatched['dispatched']);
    }

    public function testSave()
    {
        $model = new \ArrayObject();

        $this->molino
            ->expects($this->once())
            ->method('save')
            ->with($model)
        ;

        $this->eventMolino->save($model);
    }

    public function testSavePrePostSaveEvents()
    {
        $model = new \ArrayObject();
        $modelModified = new \ArrayObject();

        $this->molino
            ->expects($this->once())
            ->method('save')
            ->with($modelModified)
        ;

        $testCase = $this;
        $dispatched = new \ArrayObject(array('pre' => false, 'post' => false));
        $molino = $this->molino;


        $this->eventDispatcher->addListener(Events::PRE_SAVE,
            function ($event) use ($testCase, $dispatched, $molino, $model, $modelModified) {
                $dispatched['pre'] = true;
                $testCase->assertInstanceOf('Molino\Event\ModelEvent', $event);
                $testCase->assertSame($molino, $event->getMolino());
                $testCase->assertSame($model, $event->getModel());
                $event->setModel($modelModified);
            }
        );
        $this->eventDispatcher->addListener(Events::POST_SAVE,
            function ($event) use ($testCase, $dispatched, $molino, $modelModified) {
                $dispatched['post'] = true;
                $testCase->assertTrue($dispatched['pre']);
                $testCase->assertInstanceOf('Molino\Event\ModelEvent', $event);
                $testCase->assertSame($molino, $event->getMolino());
                $testCase->assertSame($modelModified, $event->getModel());
            }
        );

        $this->eventMolino->save($model);
        $this->assertTrue($dispatched['post']);
    }

    public function testRefresh()
    {
        $model = new \ArrayObject();

        $this->molino
            ->expects($this->once())
            ->method('refresh')
            ->with($model)
        ;

        $this->eventMolino->refresh($model);
    }

    public function testRefreshPrePostRefreshEvents()
    {
        $model = new \ArrayObject();
        $modelModified = new \ArrayObject();

        $this->molino
            ->expects($this->once())
            ->method('refresh')
            ->with($modelModified)
        ;

        $testCase = $this;
        $dispatched = new \ArrayObject(array('pre' => false, 'post' => false));
        $molino = $this->molino;


        $this->eventDispatcher->addListener(Events::PRE_REFRESH,
            function ($event) use ($testCase, $dispatched, $molino, $model, $modelModified) {
                $dispatched['pre'] = true;
                $testCase->assertInstanceOf('Molino\Event\ModelEvent', $event);
                $testCase->assertSame($molino, $event->getMolino());
                $testCase->assertSame($model, $event->getModel());
                $event->setModel($modelModified);
            }
        );
        $this->eventDispatcher->addListener(Events::POST_REFRESH,
            function ($event) use ($testCase, $dispatched, $molino, $modelModified) {
                $dispatched['post'] = true;
                $testCase->assertTrue($dispatched['pre']);
                $testCase->assertInstanceOf('Molino\Event\ModelEvent', $event);
                $testCase->assertSame($molino, $event->getMolino());
                $testCase->assertSame($modelModified, $event->getModel());
            }
        );

        $this->eventMolino->refresh($model);
        $this->assertTrue($dispatched['post']);
    }

    public function testDelete()
    {
        $model = new \ArrayObject();

        $this->molino
            ->expects($this->once())
            ->method('delete')
            ->with($model)
        ;

        $this->eventMolino->delete($model);
    }

    public function testDeletePrePostDeleteEvents()
    {
        $model = new \ArrayObject();
        $modelModified = new \ArrayObject();

        $this->molino
            ->expects($this->once())
            ->method('delete')
            ->with($modelModified)
        ;

        $testCase = $this;
        $dispatched = new \ArrayObject(array('pre' => false, 'post' => false));
        $molino = $this->molino;


        $this->eventDispatcher->addListener(Events::PRE_DELETE,
            function ($event) use ($testCase, $dispatched, $molino, $model, $modelModified) {
                $dispatched['pre'] = true;
                $testCase->assertInstanceOf('Molino\Event\ModelEvent', $event);
                $testCase->assertSame($molino, $event->getMolino());
                $testCase->assertSame($model, $event->getModel());
                $event->setModel($modelModified);
            }
        );
        $this->eventDispatcher->addListener(Events::POST_DELETE,
            function ($event) use ($testCase, $dispatched, $molino, $modelModified) {
                $dispatched['post'] = true;
                $testCase->assertTrue($dispatched['pre']);
                $testCase->assertInstanceOf('Molino\Event\ModelEvent', $event);
                $testCase->assertSame($molino, $event->getMolino());
                $testCase->assertSame($modelModified, $event->getModel());
            }
        );

        $this->eventMolino->delete($model);
        $this->assertTrue($dispatched['post']);
    }

    public function testCreateSelectQuery()
    {
        $modelClass = 'Model\Article';
        $selectQuery = $this->getMock('Molino\SelectQueryInterface');

        $this->molino
            ->expects($this->once())
            ->method('createSelectQuery')
            ->with($modelClass)
            ->will($this->returnValue($selectQuery))
        ;

        $this->assertSame($selectQuery, $this->eventMolino->createSelectQuery($modelClass));
    }

    public function testCreateSelectQueryCreateQueryEvent()
    {
        $query = $this->getMock('Molino\SelectQueryInterface');
        $queryModified = $this->getMock('Molino\SelectQueryInterface');

        $this->molino
            ->expects($this->once())
            ->method('createSelectQuery')
            ->will($this->returnValue($query))
        ;

        $testCase = $this;
        $dispatched = new \ArrayObject(array('dispatched' => false));
        $molino = $this->molino;
        $this->eventDispatcher->addListener(Events::CREATE_QUERY,
            function ($event) use ($testCase, $dispatched, $molino, $query, $queryModified) {
                $dispatched['dispatched'] = true;
                $testCase->assertInstanceOf('Molino\Event\QueryEvent', $event);
                $testCase->assertSame($molino, $event->getMolino());
                $testCase->assertSame($query, $event->getQuery());
                $event->setQuery($queryModified);
            }
        );

        $this->assertSame($queryModified, $this->eventMolino->createSelectQuery('Model\Article'));
        $this->assertTrue($dispatched['dispatched']);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testCreateSelectQueryModifiedQueryNotSelectQuery()
    {
        $query = $this->getMock('Molino\SelectQueryInterface');
        $queryModified = $this->getMock('Molino\UpdateQueryInterface');

        $this->molino
            ->expects($this->any())
            ->method('createSelectQuery')
            ->will($this->returnValue($query))
        ;

        $this->eventDispatcher->addListener(Events::CREATE_QUERY, function ($event) use ($queryModified) {
            $event->setQuery($queryModified);
        });

        $this->eventMolino->createSelectQuery('Model\Article');
    }

    public function testCreateUpdateQuery()
    {
        $modelClass = 'Model\Article';
        $updateQuery = $this->getMock('Molino\UpdateQueryInterface');

        $this->molino
            ->expects($this->once())
            ->method('createUpdateQuery')
            ->with($modelClass)
            ->will($this->returnValue($updateQuery))
        ;

        $this->assertSame($updateQuery, $this->eventMolino->createUpdateQuery($modelClass));
    }

    public function testCreateUpdateQueryCreateQueryEvent()
    {
        $query = $this->getMock('Molino\UpdateQueryInterface');
        $queryModified = $this->getMock('Molino\UpdateQueryInterface');

        $this->molino
            ->expects($this->once())
            ->method('createUpdateQuery')
            ->will($this->returnValue($query))
        ;

        $testCase = $this;
        $dispatched = new \ArrayObject(array('dispatched' => false));
        $molino = $this->molino;
        $this->eventDispatcher->addListener(Events::CREATE_QUERY,
            function ($event) use ($testCase, $dispatched, $molino, $query, $queryModified) {
                $dispatched['dispatched'] = true;
                $testCase->assertInstanceOf('Molino\Event\QueryEvent', $event);
                $testCase->assertSame($molino, $event->getMolino());
                $testCase->assertSame($query, $event->getQuery());
                $event->setQuery($queryModified);
            }
        );

        $this->assertSame($queryModified, $this->eventMolino->createUpdateQuery('Model\Article'));
        $this->assertTrue($dispatched['dispatched']);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testCreateUpdateQueryModifiedQueryNotUpdateQuery()
    {
        $query = $this->getMock('Molino\UpdateQueryInterface');
        $queryModified = $this->getMock('Molino\SelectQueryInterface');

        $this->molino
            ->expects($this->any())
            ->method('createUpdateQuery')
            ->will($this->returnValue($query))
        ;

        $this->eventDispatcher->addListener(Events::CREATE_QUERY, function ($event) use ($queryModified) {
            $event->setQuery($queryModified);
        });

        $this->eventMolino->createUpdateQuery('Model\Article');
    }

    public function testCreateDeleteQuery()
    {
        $modelClass = 'Model\Article';
        $deleteQuery = $this->getMock('Molino\DeleteQueryInterface');

        $this->molino
            ->expects($this->once())
            ->method('createDeleteQuery')
            ->with($modelClass)
            ->will($this->returnValue($deleteQuery))
        ;

        $this->assertSame($deleteQuery, $this->eventMolino->createDeleteQuery($modelClass));
    }

    public function testCreateDeleteQueryCreateQueryEvent()
    {
        $query = $this->getMock('Molino\DeleteQueryInterface');
        $queryModified = $this->getMock('Molino\DeleteQueryInterface');

        $this->molino
            ->expects($this->once())
            ->method('createDeleteQuery')
            ->will($this->returnValue($query))
        ;

        $testCase = $this;
        $dispatched = new \ArrayObject(array('dispatched' => false));
        $molino = $this->molino;
        $this->eventDispatcher->addListener(Events::CREATE_QUERY,
            function ($event) use ($testCase, $dispatched, $molino, $query, $queryModified) {
                $dispatched['dispatched'] = true;
                $testCase->assertInstanceOf('Molino\Event\QueryEvent', $event);
                $testCase->assertSame($molino, $event->getMolino());
                $testCase->assertSame($query, $event->getQuery());
                $event->setQuery($queryModified);
            }
        );

        $this->assertSame($queryModified, $this->eventMolino->createDeleteQuery('Model\Article'));
        $this->assertTrue($dispatched['dispatched']);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testCreateDeleteQueryModifiedQueryNotDeleteQuery()
    {
        $query = $this->getMock('Molino\DeleteQueryInterface');
        $queryModified = $this->getMock('Molino\SelectQueryInterface');

        $this->molino
            ->expects($this->any())
            ->method('createDeleteQuery')
            ->will($this->returnValue($query))
        ;

        $this->eventDispatcher->addListener(Events::CREATE_QUERY, function ($event) use ($queryModified) {
            $event->setQuery($queryModified);
        });

        $this->eventMolino->createDeleteQuery('Model\Article');
    }

    public function testFindOneById()
    {
        $modelClass = 'Model\Article';
        $id = 'foo';
        $model = new \ArrayObject();

        $this->molino
            ->expects($this->once())
            ->method('findOneById')
            ->with($modelClass, $id)
            ->will($this->returnValue($model))
        ;

        $this->assertSame($model, $this->eventMolino->findOneById($modelClass, $id));
    }
}
