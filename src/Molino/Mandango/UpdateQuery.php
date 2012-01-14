<?php

/*
 * This file is part of the Molino package.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Molino\Mandango;

use Molino\UpdateQueryInterface;
use Mandango\Repository;

/**
 * The update query for Mandango.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
class UpdateQuery extends BaseQuery implements UpdateQueryInterface
{
    private $repository;
    private $newObject;

    /**
     * Constructor.
     *
     * @param Repository $repository A repository.
     */
    public function __construct(Repository $repository)
    {
        parent::__construct();

        $this->repository = $repository;
        $this->newObject = array();
    }

    /**
     * Returns the repository.
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Returns the new object.
     *
     * The new object is the array used as $newObject in the update method of mongo collections.
     *
     * @return array The new object.
     */
    public function getNewObject()
    {
        return $this->newObject;
    }

    /**
     * {@inheritdoc}
     */
    public function set($field, $value)
    {
        $this->newObject['$set'][$field] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function inc($field, $inc)
    {
        $this->newObject['$inc'][$field] = $inc;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $this->repository->update($this->getCriteria(), $this->getNewObject());
    }
}
