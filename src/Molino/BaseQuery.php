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
 * BaseQuery.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
abstract class BaseQuery implements QueryInterface
{
    static private $filterMethods = array(
        '=='     => 'filterEqual',
        '!='     => 'filterNotEqual',
        'in'     => 'filterIn',
        'not_in' => 'filterNotIn',
        '>'      => 'filterGreater',
        '<'      => 'filterLess',
        '>='     => 'filterGreaterEqual',
        '<='     => 'filterLessEqual',
    );

    /**
     * Shortcut for filter methods.
     *
     * The comparison supported are:
     *
     *   * "==": filterEqual
     *   * "!=": filterNotEqual
     *   * "in": filterIn
     *   * "not_in": filterNotIn
     *   * ">": filterGreater
     *   * "<": filterLess
     *   * ">=": filterGreaterEqual
     *   * ">=": filterLessEqual
     *
     * @param string $field      The field.
     * @param string $comparison The comparison.
     * @param mixed  $value      The value.
     *
     * @return QueryInterface The query (fluent interface).
     *
     * @throws \InvalidArgumentException If the comparison is not supported.
     */
    public function filter($field, $comparison, $value)
    {
        if (!isset(self::$filterMethods[$comparison])) {
            throw new \InvalidArgumentException(sprintf('The comparison "%s" is not supported.', $comparison));
        }
        $method = self::$filterMethods[$comparison];

        $this->$method($field, $value);

        return $this;
    }
}
