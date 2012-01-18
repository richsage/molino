<?php

/*
 * This file is part of the Molino package.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Molino\Event;

use Molino\MolinoInterface;

/**
 * ModelEvent.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
class ModelEvent extends Event
{
    private $model;

    /**
     * Constructor.
     *
     * @param MolinoInterface $molino A molino.
     * @param object          $model  A model.
     */
    public function __construct(MolinoInterface $molino, $model)
    {
        parent::__construct($molino);

        $this->model = $model;
    }

    /**
     * Returns the model.
     *
     * @return object The model.
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Sets the model
     *
     * @param object $model A model.
     */
    public function setModel($model)
    {
        $this->model = $model;
    }
}
