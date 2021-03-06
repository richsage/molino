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
use Molino\MolinoInterface;

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
     * {@inheritdoc}
     */
    public function __construct(MolinoInterface $molino, $modelClass)
    {
        parent::__construct($molino, $modelClass);

        $this->criteria = array();
    }

    /**
     * {@inheritdoc}
     */
    public function setMolino(MolinoInterface $molino)
    {
        if (!$molino instanceof Molino) {
            throw new \InvalidArgumentException('The molino must be an instance of Molino\Mandango\Molino.');
        }

        parent::setMolino($molino);
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
    public function filterLike($field, $value)
    {
        $field = $this->parseField($field);

        $pattern = $this->buildLikePattern($value);

        $this->criteria[$field] = new \MongoRegex($pattern);
        $this->criteriaModified();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function filterNotLike($field, $value)
    {
        $field = $this->parseField($field);

        $pattern = $this->buildLikePattern($value);

        $this->criteria[$field] = array('$not' => new \MongoRegex($pattern));
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

    private function buildLikePattern($value)
    {
        $start = false;
        $pattern = '';
        $end = false;

        $parsed = $this->parseLike($value);
        reset($parsed);
        if ('*' !== current($parsed)) {
            $start = true;
        }
        end($parsed);
        if ('*' !== current($parsed)) {
            $end = true;
        }

        foreach ($parsed as $v) {
            if ('*' === $v) {
                $pattern .= '.*';
            } else {
                $pattern .= $v;
            }
        }

        if ($start) {
            $pattern = '^'.$pattern;
        }
        if ($end) {
            $pattern .= '$';
        }

        $pattern = '/'.$pattern.'/';

        return $pattern;
    }
}
