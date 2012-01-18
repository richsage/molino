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
use Molino\MolinoInterface;

/**
 * The update query for Mandango.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
class UpdateQuery extends BaseQuery implements UpdateQueryInterface
{
    private $newObject;

    /**
     * {@inheritdoc}
     */
    public function __construct(MolinoInterface $molino, $modelClass)
    {
        parent::__construct($molino, $modelClass);

        $this->newObject = array();
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
        $this
            ->getMolino()
            ->getMandango()
            ->getRepository($this->getModelClass())
            ->update($this->getCriteria(), $this->getNewObject())
        ;
    }
}
