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

/**
 * MolinoInterface.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
interface MolinoInterface
{
    /**
     * Returns the name.
     *
     * @return string The name.
     */
    function getName();

    /**
     * Creates a model.
     *
     * @param string $modelClass The model class.
     *
     * @return object The model.
     */
    function create($modelClass);

    /**
     * Saves a model.
     *
     * @param object $model The model.
     */
    function save($model);

    /**
     * Refreshes a model.
     *
     * @param object $model The model.
     */
    function refresh($model);

    /**
     * Deletes a model.
     *
     * @param object $model The model.
     */
    function delete($model);

    /**
     * Creates a select query.
     *
     * @param string $modelClass The model class.
     *
     * @return SelectQueryInterface A select query.
     */
    function createSelectQuery($modelClass);

    /**
     * Creates an update query.
     *
     * @param string $modelClass The model class.
     *
     * @return UpdateQueryInterface An update query.
     */
    function createUpdateQuery($modelClass);

    /**
     * Creates a delete query.
     *
     * @param string $modelClass The model class.
     *
     * @return DeleteQueryInterface A delete query.
     */
    function createDeleteQuery($modelClass);

    /**
     * Finds a model by id.
     *
     * @param string $modelClass The model class.
     * @param mixed  $id         The id.
     *
     * @return object|null The model or NULL if it does not exist.
     */
    function findOneById($modelClass, $id);
}
