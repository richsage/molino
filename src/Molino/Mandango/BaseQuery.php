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

use Molino\QueryInterface;

/**
 * The base query for Mandango.
 *
 * The field "id" is converted automatically to "_id" to get compatibility across backends.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
abstract class BaseQuery implements QueryInterface
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->criteria = array();
    }

    /**
     * Returns the criteria.
     *
     * @return array The criteria.
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * {@inheritdoc}
     */
    public function filterEqual($field, $value)
    {
        $field = $this->parseField($field);

        $this->criteria[$field] = $value;
        $this->criteriaModified();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function filterNotEqual($field, $value)
    {
        $field = $this->parseField($field);

        $this->criteria[$field] = array('$ne' => $value);
        $this->criteriaModified();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function filterIn($field, array $values)
    {
        $field = $this->parseField($field);

        $this->criteria[$field] = array('$in' => $values);
        $this->criteriaModified();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function filterNotIn($field, array $values)
    {
        $field = $this->parseField($field);

        $this->criteria[$field] = array('$nin' => $values);
        $this->criteriaModified();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function filterGreater($field, $value)
    {
        $field = $this->parseField($field);

        $this->criteria[$field] = array('$gt' => $value);
        $this->criteriaModified();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function filterLess($field, $value)
    {
        $field = $this->parseField($field);

        $this->criteria[$field] = array('$lt' => $value);
        $this->criteriaModified();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function filterGreaterEqual($field, $value)
    {
        $field = $this->parseField($field);

        $this->criteria[$field] = array('$gte' => $value);
        $this->criteriaModified();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function filterLessEqual($field, $value)
    {
        $field = $this->parseField($field);

        $this->criteria[$field] = array('$lte' => $value);
        $this->criteriaModified();

        return $this;
    }

    /**
     * This method is called when the criteria is modified by a filter
     */
    protected function criteriaModified()
    {
    }

    protected function parseField($field)
    {
        if ('id' === $field) {
            $field = '_id';
        }

        return $field;
    }
}
