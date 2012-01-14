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

use Molino\MolinoInterface;
use Mandango\Mandango;

/**
 * The molino for Mandango.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
class Molino implements MolinoInterface
{
    private $mandango;

    /**
     * Constructor.
     *
     * @param Mandango $mandango A mandango.
     */
    public function __construct(Mandango $mandango)
    {
        $this->mandango = $mandango;
    }

    /**
     * Returns the mandango.
     *
     * @return Mandango The mandango.
     */
    public function getMandango()
    {
        return $this->mandango;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'mandango';
    }

    /**
     * {@inheritdoc}
     */
    public function createModel($modelClass)
    {
        return $this->mandango->create($modelClass);
    }

    /**
     * {@inheritdoc}
     */
    public function saveModel($model)
    {
        $model->save();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteModel($model)
    {
        $model->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function createSelectQuery($modelClass)
    {
        return new SelectQuery($this->mandango->getRepository($modelClass)->createQuery());
    }

    /**
     * {@inheritdoc}
     */
    public function createUpdateQuery($modelClass)
    {
        return new UpdateQuery($this->mandango->getRepository($modelClass));
    }

    /**
     * {@inheritdoc}
     */
    public function createDeleteQuery($modelClass)
    {
        return new DeleteQuery($this->mandango->getRepository($modelClass));
    }

    /**
     * {@inheritdoc}
     */
    public function findOneById($modelClass, $id)
    {
        return $this->mandango->getRepository($modelClass)->findOneById($id);
    }
}
