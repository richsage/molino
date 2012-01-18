<?php

namespace Molino\Doctrine\ORM;

use Molino\MolinoInterface;
use Doctrine\ORM\EntityManager;

/**
 * The molino for Doctrine ORM.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
class Molino implements MolinoInterface
{
    private $entityManager;

    /**
     * Constructor.
     *
     * @param EntityManager A entity manager.
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Returns the entity manager.
     *
     * @return EntityManager The entity manager.
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'doctrine_orm';
    }

    /**
     * {@inheritdoc}
     */
    public function create($class)
    {
        return new $class;
    }

    /**
     * {@inheritdoc}
     */
    public function save($model)
    {
        $this->entityManager->persist($model);
        $this->entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function refresh($model)
    {
        $this->entityManager->refresh($model);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($model)
    {
        $this->entityManager->remove($model);
        $this->entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function createSelectQuery($modelClass)
    {
        return new SelectQuery($this, $modelClass);
    }

    /**
     * {@inheritdoc}
     */
    public function createUpdateQuery($modelClass)
    {
        return new UpdateQuery($this, $modelClass);
    }

    /**
     * {@inheritdoc}
     */
    public function createDeleteQuery($modelClass)
    {
        return new DeleteQuery($this, $modelClass);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneById($modelClass, $id)
    {
        return $this->entityManager->find($modelClass, $id);
    }
}
