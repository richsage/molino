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
     * Adds a filter.
     *
     * The comparison supported are:
     *
     *   * "=="
     *   * "!="
     *   * "in": requires an array as value.
     *   * "not_in": requires an array as value.
     *   * ">"
     *   * "<"
     *   * ">="
     *   * ">="
     *
     * @param string $field      The field.
     * @param string $comparison The comparison.
     * @param mixed  $value      The value.
     *
     * @return QueryInterface The query (fluent interface).
     *
     * @throws \InvalidArgumentException If the comparison is not supported.
     * @throws \InvalidArgumentException If the value must be an array and it is not.
     */
    function filter($name, $comparation, $value);
}
