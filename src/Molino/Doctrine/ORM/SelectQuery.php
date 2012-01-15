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

use Molino\SelectQueryInterface;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;

/**
 * The select query for Doctrine ORM.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
class SelectQuery extends BaseQuery implements SelectQueryInterface
{
    /**
     * {@inheritdoc}
     */
    protected function configureQueryBuilder(QueryBuilder $queryBuilder)
    {
        $queryBuilder->select($queryBuilder->getRootAlias());
    }

    /**
     * {@inheritdoc}
     */
    public function fields(array $fields)
    {
        $rootAlias = $this->getQueryBuilder()->getRootAlias();
        foreach ($fields as &$field) {
            if (!is_string($field)) {
                throw new \InvalidArgumentException('Fields must be strings.');
            }

            $field = $rootAlias.'.'.$field;
        }

        $this->getQueryBuilder()->select($fields);

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

        $this->getQueryBuilder()->orderBy($this->getQueryBuilder()->getRootAlias().'.'.$field, strtoupper($order));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function limit($limit)
    {
        $this->getQueryBuilder()->setMaxResults($limit);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function skip($skip)
    {
        $this->getQueryBuilder()->setFirstResult($skip);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->getQueryBuilder()->getQuery()->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function one()
    {
        $this->limit(1);

        return $this->getQueryBuilder()->getQuery()->getOneOrNullResult();
    }

    public function count()
    {
        $this->getQueryBuilder()->select(sprintf('COUNT(%s.id) as nb', $this->getQueryBuilder()->getRootAlias()));
        $this->limit(1);

        $result = $this->getQueryBuilder()->getQuery()->getScalarResult();

        return (int) $result[0]['nb'];
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->all());
    }

    /**
     * {@inheritdoc}
     */
    public function createPagerfantaAdapter()
    {
        return new DoctrineORMAdapter($this->getQueryBuilder());
    }
}
