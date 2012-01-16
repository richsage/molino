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
     * {@inheritdoc}
     */
    public function filter($field, $comparison, $value)
    {
        $field = $this->parseField($field);

        if (!isset(self::$filterMethods[$comparison])) {
            throw new \InvalidArgumentException(sprintf('The comparison "%s" is not supported.', $comparison));
        }
        $method = self::$filterMethods[$comparison];

        if (in_array($comparison, array('in', 'not_in')) && !is_array($value)) {
            throw new \InvalidArgumentException(sprintf('The value must be an array in the "%s" comparison.', $comparison));
        }

        $this->$method($field, $value);

        return $this;
    }

    /**
     * Parses a field.
     *
     * @param string $field A field.
     *
     * @return The field parsed.
     */
    protected function parseField($field)
    {
        return $field;
    }

    /**
     * Adds a filter equal.
     *
     * @param string $field The field.
     * @param mixed  $value The value.
     */
    abstract protected function filterEqual($field, $value);

    /**
     * Adds a filter no equal.
     *
     * @param string $field The field.
     * @param mixed  $value The value.
     */
    abstract protected function filterNotEqual($field, $value);

    /**
     * Adds a filter in.
     *
     * @param string $field  The field.
     * @param array  $values The values.
     */
    abstract protected function filterIn($field, array $values);

    /**
     * Adds a filter not in.
     *
     * @param string $field  The field.
     * @param array  $values The values.
     */
    abstract protected function filterNotIn($field, array $values);

    /**
     * Adds a filter greater.
     *
     * @param string $field The field.
     * @param mixed  $value The value.
     */
    abstract protected function filterGreater($field, $value);

    /**
     * Adds a filter less.
     *
     * @param string $field The field.
     * @param mixed  $value The value.
     */
    abstract protected function filterLess($field, $value);

    /**
     * Adds a filter greater equal.
     *
     * @param string $field The field.
     * @param mixed  $value The value.
     */
    abstract protected function filterGreaterEqual($field, $value);

    /**
     * Adds a filter less equal.
     *
     * @param string $field The field.
     * @param mixed  $value The value.
     */
    abstract protected function filterLessEqual($field, $value);
}
