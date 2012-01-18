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
use Pagerfanta\Adapter\MandangoAdapter;

/**
 * The select query for Mandango.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
class SelectQuery extends BaseQuery implements SelectQueryInterface
{
    private $mandangoQuery;

    /**
     * Returns the mandango query.
     *
     * @return Query The mandango query.
     */
    public function getMandangoQuery()
    {
        if (null === $this->mandangoQuery) {
            $this->mandangoQuery = $this
                ->getMolino()
                ->getMandango()
                ->getRepository($this->getModelClass())
                ->createQuery()
            ;
        }

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

        $this->getMandangoQuery()->fields($parsedFields);

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

        $this->getMandangoQuery()->sort(array($field => 'asc' === $order ? 1 : -1));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function limit($limit)
    {
        $this->getMandangoQuery()->limit($limit);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function skip($skip)
    {
        $this->getMandangoQuery()->skip($skip);

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
        return $this->getMandangoQuery()->getIterator();
    }

    /**
     * {@inheritdoc}
     */
    public function createPagerfantaAdapter()
    {
        return new MandangoAdapter($this->getMandangoQuery());
    }

    protected function criteriaModified()
    {
        $this->getMandangoQuery()->criteria($this->getCriteria());
    }
}
