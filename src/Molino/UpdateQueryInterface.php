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
 * UpdateQueryInterface.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
interface UpdateQueryInterface extends QueryInterface
{
    /**
     * Sets a field value.
     *
     * @param string $field The field.
     * @param mixed  $value The value.
     *
     * @return UpdateQueryInterface The query (fluent interface).
     */
    function set($field, $value);

    /**
     * Increments a field value.
     *
     * @param string  $field The field.
     * @param integer $inc The increment.
     *
     * @return UpdateQueryInterface The query (fluent interface).
     */
    function inc($field, $inc);

    /**
     * Executes the query.
     */
    function execute();
}
