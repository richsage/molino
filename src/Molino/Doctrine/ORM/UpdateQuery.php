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

use Molino\UpdateQueryInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * The update query for Doctrine ORM.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
class UpdateQuery extends BaseQuery implements UpdateQueryInterface
{
    /**
     * {@inheritdoc}
     */
    protected function configureQueryBuilder(QueryBuilder $queryBuilder)
    {
        $queryBuilder->update();
    }

    /**
     * {@inheritdoc}
     */
    public function set($field, $value)
    {
        $parameterId = $this->generateParameterId();
        $rootAlias = $this->getQueryBuilder()->getRootAlias();
        $this->getQueryBuilder()->set(sprintf('%s.%s', $rootAlias, $field), '?'.$parameterId);
        $this->getQueryBuilder()->setParameter($parameterId, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function inc($field, $inc)
    {
        $rootAlias = $this->getQueryBuilder()->getRootAlias();
        $this->getQueryBuilder()->set(sprintf('%s.%s', $rootAlias, $field), sprintf('%s.%s + %d', $rootAlias, $field, $inc));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $this->getQueryBuilder()->getQuery()->execute();
    }
}
