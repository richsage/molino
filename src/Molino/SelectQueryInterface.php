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
 * SelectQueryInterface.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
interface SelectQueryInterface extends QueryInterface, \Countable, \IteratorAggregate
{
    /**
     * Sets the fields to select.
     *
     * @param array $fields The fields.
     *
     * @return SelectQueryInterface The query (fluent interface)
     */
    function fields(array $fields);

    /**
     * Sets the sort.
     *
     * @param string $field The field.
     * @param string $order The order, 'asc' or 'desc'.
     *
     * @return SelectQueryInterface The query (fluent interface).
     *
     * @throws \InvalidArgumentException If the order is not neither 'asc' nor 'desc'.
     */
    function sort($field, $order = 'asc');

    /**
     * Sets the limit of results.
     *
     * @param integer $limit The limit.
     *
     * @return SelectQueryInterface The query (fluent interface).
     */
    function limit($limit);

    /**
     * Sets the number of results to skip (offset).
     *
     * @param integer $skip The number of results to skip.
     *
     * @return SelectQueryInterface The query (fluent interface).
     */
    function skip($skip);

    /**
     * Returns all results.
     *
     * @return array All results.
     */
    function all();

    /**
     * Returns one result.
     *
     * @return object|null One result or null if there is no results.
     */
    function one();

    /**
     * Returns a Pagerfanta adapter.
     *
     * https://github.com/whiteoctober/Pagerfanta
     *
     * @return AdapterInterface The adapter.
     */
    function createPagerfantaAdapter();
}
