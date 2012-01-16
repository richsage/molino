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

use Molino\BaseQuery as BaseBaseQuery;

/**
 * The base query for Mandango.
 *
 * The field "id" is converted automatically to "_id" to get compatibility across backends.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
abstract class BaseQuery extends BaseBaseQuery
{
    private $criteria;

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
    protected function parseField($field)
    {
        if ('id' === $field) {
            $field = '_id';
        }

        return $field;
    }

    /**
     * {@inheritdoc}
     */
    protected function filterEqual($field, $value)
    {
        $this->criteria[$field] = $value;
        $this->criteriaModified();
    }

    /**
     * {@inheritdoc}
     */
    protected function filterNotEqual($field, $value)
    {
        $this->criteria[$field] = array('$ne' => $value);
        $this->criteriaModified();
    }

    /**
     * {@inheritdoc}
     */
    protected function filterIn($field, array $values)
    {
        $this->criteria[$field] = array('$in' => $values);
        $this->criteriaModified();
    }

    /**
     * {@inheritdoc}
     */
    protected function filterNotIn($field, array $values)
    {
        $this->criteria[$field] = array('$nin' => $values);
        $this->criteriaModified();
    }

    /**
     * {@inheritdoc}
     */
    protected function filterGreater($field, $value)
    {
        $this->criteria[$field] = array('$gt' => $value);
        $this->criteriaModified();
    }

    /**
     * {@inheritdoc}
     */
    protected function filterLess($field, $value)
    {
        $this->criteria[$field] = array('$lt' => $value);
        $this->criteriaModified();
    }

    /**
     * {@inheritdoc}
     */
    protected function filterGreaterEqual($field, $value)
    {
        $this->criteria[$field] = array('$gte' => $value);
        $this->criteriaModified();
    }

    /**
     * {@inheritdoc}
     */
    protected function filterLessEqual($field, $value)
    {
        $this->criteria[$field] = array('$lte' => $value);
        $this->criteriaModified();
    }

    /**
     * This method is called when the criteria is modified by a filter
     */
    protected function criteriaModified()
    {
    }
}
