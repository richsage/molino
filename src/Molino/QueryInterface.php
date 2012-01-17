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
 * QueryInterface.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
interface QueryInterface
{
    /**
     * Adds a filter equal.
     *
     * @param string $field The field.
     * @param mixed  $value The value.
     *
     * @return QueryInterface The query (fluent interface).
     */
    function filterEqual($field, $value);

    /**
     * Adds a filter no equal.
     *
     * @param string $field The field.
     * @param mixed  $value The value.
     *
     * @return QueryInterface The query (fluent interface).
     */
    function filterNotEqual($field, $value);

    /**
     * Adds a filter in.
     *
     * @param string $field  The field.
     * @param array  $values The values.
     *
     * @return QueryInterface The query (fluent interface).
     */
    function filterIn($field, array $values);

    /**
     * Adds a filter not in.
     *
     * @param string $field  The field.
     * @param array  $values The values.
     *
     * @return QueryInterface The query (fluent interface).
     */
    function filterNotIn($field, array $values);

    /**
     * Adds a filter greater.
     *
     * @param string $field The field.
     * @param mixed  $value The value.
     *
     * @return QueryInterface The query (fluent interface).
     */
    function filterGreater($field, $value);

    /**
     * Adds a filter less.
     *
     * @param string $field The field.
     * @param mixed  $value The value.
     *
     * @return QueryInterface The query (fluent interface).
     */
    function filterLess($field, $value);

    /**
     * Adds a filter greater equal.
     *
     * @param string $field The field.
     * @param mixed  $value The value.
     *
     * @return QueryInterface The query (fluent interface).
     */
    function filterGreaterEqual($field, $value);

    /**
     * Adds a filter less equal.
     *
     * @param string $field The field.
     * @param mixed  $value The value.
     *
     * @return QueryInterface The query (fluent interface).
     */
    function filterLessEqual($field, $value);
}
