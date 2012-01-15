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

use Molino\SelectQueryInterface;
use Mandango\Query;

/**
 * The select query for Mandango.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
class SelectQuery extends BaseQuery implements SelectQueryInterface
{
    private $mandangoQuery;

    /**
     * Constructor.
     *
     * @param Query $mandangoQuery A mandango query.
     */
    public function __construct(Query $mandangoQuery)
    {
        parent::__construct();

        $this->mandangoQuery = $mandangoQuery;
    }

    /**
     * Returns the mandango query.
     *
     * @return Query The mandango query.
     */
    public function getMandangoQuery()
    {
        return $this->mandangoQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function fields(array $fields)
    {
        $parsedFields = array();
        foreach ($fields as $field) {
            if (!is_string($field)) {
                throw new \InvalidArgumentException('The fields must be strings.');
            }

            $parsedFields[$field] = 1;
        }

        $this->mandangoQuery->fields($parsedFields);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function sort($field, $order = 'asc')
    {
        if (!in_array($order, array('asc', 'desc'))) {
            throw new \InvalidArgumentException(sprintf('The order "%s" is not valid.', $order));
        }

        $field = $this->parseField($field);

        $this->mandangoQuery->sort(array($field => 'asc' === $order ? 1 : -1));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function limit($limit)
    {
        $this->mandangoQuery->limit($limit);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function skip($skip)
    {
        $this->mandangoQuery->skip($skip);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->getMandangoQuery()->all();
    }

    /**
     * {@inheritdoc}
     */
    public function one()
    {
        return $this->getMandangoQuery()->one();
    }

    public function count()
    {
        return $this->getMandangoQuery()->count();
    }

    public function getIterator()
    {
        return $this->mandangoQuery->getIterator();
    }

    protected function criteriaModified()
    {
        $this->mandangoQuery->setCriteria($this->getCriteria());
    }
}
