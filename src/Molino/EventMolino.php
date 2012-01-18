<?php

/*
 * This file is part of the Molino package.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Molino;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Molino\Event\Events;
use Molino\Event\ModelEvent;
use Molino\Event\QueryEvent;

/**
 * EventMolino wraps other molino for adding events to it.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
class EventMolino implements MolinoInterface
{
    private $molino;
    private $eventDispatcher;

    /**
     * Constructor.
     *
     * @param MolinoInterface          $molino          A molino.
     * @param EventDispatcherInterface $eventDispatcher An event dispatcher.
     */
    public function __construct(MolinoInterface $molino, EventDispatcherInterface $eventDispatcher)
    {
        $this->molino = $molino;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Returns the molino.
     *
     * @return MolinoInterface The molino.
     */
    public function getMolino()
    {
        return $this->molino;
    }

    /**
     * Returns the event dispatcher.
     *
     * @return EventDispatcherInterface The event dispatcher.
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->molino->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function create($modelClass)
    {
        $model = $this->molino->create($modelClass);

        $event = new ModelEvent($this->molino, $model);
        $this->eventDispatcher->dispatch(Events::CREATE, $event);
        $model = $event->getModel();

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function save($model)
    {
        $event = new ModelEvent($this->molino, $model);
        $this->eventDispatcher->dispatch(Events::PRE_SAVE, $event);
        $model = $event->getModel();

        $this->molino->save($model);

        $event = new ModelEvent($this->molino, $model);
        $this->eventDispatcher->dispatch(Events::POST_SAVE, $event);
    }

    /**
     * {@inheritdoc}
     */
    public function refresh($model)
    {
        $event = new ModelEvent($this->molino, $model);
        $this->eventDispatcher->dispatch(Events::PRE_REFRESH, $event);
        $model = $event->getModel();

        $this->molino->refresh($model);

        $event = new ModelEvent($this->molino, $model);
        $this->eventDispatcher->dispatch(Events::POST_REFRESH, $event);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($model)
    {
        $event = new ModelEvent($this->molino, $model);
        $this->eventDispatcher->dispatch(Events::PRE_DELETE, $event);
        $model = $event->getModel();

        $this->molino->delete($model);

        $event = new ModelEvent($this->molino, $model);
        $this->eventDispatcher->dispatch(Events::POST_DELETE, $event);
    }

    /**
     * {@inheritdoc}
     */
    public function createSelectQuery($modelClass)
    {
        $query = $this->molino->createSelectQuery($modelClass);

        $event = new QueryEvent($this->molino, $query);
        $this->eventDispatcher->dispatch(Events::CREATE_QUERY, $event);
        $query = $event->getQuery();

        if (!$query instanceof SelectQueryInterface) {
            throw new \RuntimeException('The query must be an instance of SelectQueryInterface.');
        }

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function createUpdateQuery($modelClass)
    {
        $query = $this->molino->createUpdateQuery($modelClass);

        $event = new QueryEvent($this->molino, $query);
        $this->eventDispatcher->dispatch(Events::CREATE_QUERY, $event);
        $query = $event->getQuery();

        if (!$query instanceof UpdateQueryInterface) {
            throw new \RuntimeException('The query must be an instance of UpdateQueryInterface.');
        }

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function createDeleteQuery($modelClass)
    {
        $query = $this->molino->createDeleteQuery($modelClass);

        $event = new QueryEvent($this->molino, $query);
        $this->eventDispatcher->dispatch(Events::CREATE_QUERY, $event);
        $query = $event->getQuery();

        if (!$query instanceof DeleteQueryInterface) {
            throw new \RuntimeException('The query must be an instance of DeleteQueryInterface.');
        }

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function findOneById($modelClass, $id)
    {
        return $this->molino->findOneById($modelClass, $id);
    }
}
