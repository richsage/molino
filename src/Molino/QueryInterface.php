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
     * Sets the molino.
     *
     * @param MolinoInterface A molino.
     */
    function setMolino(MolinoInterface $molino);

    /**
     * Returns the molino.
     *
     * @return MolinoInterface The molino.
     */
    function getMolino();

    /**
     * Returns the model class.
     *
     * @return string The model class.
     */
    function getModelClass();

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
     * Adds a filter like.
     *
     * The wildcard is the asterisk character.
     *
     *   * "Pablo*"
     *   * "*Pablo"
     *   * "*Pablo*"
     *
     * @param string $field The field.
     * @param string $value The value.
     *
     * @return QueryInterface The query (fluent interface).
     */
    function filterLike($field, $value);

    /**
     * Adds a filter not like.
     *
     * @param string $field The field.
     * @param string $value The value.
     *
     * @return QueryInterface The query (fluent interface).
     */
    function filterNotLike($field, $value);

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
