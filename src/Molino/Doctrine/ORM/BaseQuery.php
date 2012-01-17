<?php

/*
 * This file is part of the Molino package.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Molino\Doctrine\ORM;

use Molino\BaseQuery as BaseBaseQuery;
use Doctrine\ORM\QueryBuilder;

/**
 * The base query for Doctrine ORM.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
abstract class BaseQuery extends BaseBaseQuery
{
    private $queryBuilder;
    private $lastParameterId;

    /**
     * Constructor.
     *
     * @param QueryBuilder A query builder.
     */
    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->configureQueryBuilder($queryBuilder);

        $this->queryBuilder = $queryBuilder;
        $this->lastParameterId = 0;
    }

    /**
     * Method to configure the query builder.
     *
     * @param QueryBuilder The query builder.
     */
    protected function configureQueryBuilder(QueryBuilder $queryBuilder)
    {
    }

    /**
     * Returns the query builder.
     *
     * @return QueryBuilder The query builder.
     */
    public function getQueryBuilder()
    {
        return $this->queryBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function filterEqual($field, $value)
    {
        $this->andWhere('=', $field, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function filterNotEqual($field, $value)
    {
        $this->andWhere('<>', $field, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function filterIn($field, array $values)
    {
        $this->andWhere('IN', $field, $values);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function filterNotIn($field, array $values)
    {
        $this->andWhere('NOT IN', $field, $values);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function filterGreater($field, $value)
    {
        $this->andWhere('>', $field, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function filterLess($field, $value)
    {
        $this->andWhere('<', $field, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function filterGreaterEqual($field, $value)
    {
        $this->andWhere('>=', $field, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function filterLessEqual($field, $value)
    {
        $this->andWhere('<=', $field, $value);

        return $this;
    }

    /**
     * Generates and returns a parameter id to use in the query builder.
     *
     * @return integer A parameter id.
     */
    protected function generateParameterId()
    {
        return ++$this->lastParameterId;
    }

    private function andWhere($comparison, $field, $value)
    {
        $parameterId = $this->generateParameterId();
        $rootAlias = $this->queryBuilder->getRootAlias();
        $this->queryBuilder->andWhere(sprintf('%s.%s %s ?%d', $rootAlias, $field, $comparison, $parameterId));
        $this->queryBuilder->setParameter($parameterId, $value);
    }
}
