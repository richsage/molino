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

use Molino\DeleteQueryInterface;
use Mandango\Repository;

/**
 * The delete query for Mandango.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
class DeleteQuery extends BaseQuery implements DeleteQueryInterface
{
    private $repository;

    /**
     * Constructor.
     *
     * @param Repository $repository A repository.
     */
    public function __construct(Repository $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }

    /**
     * Returns the repository.
     *
     * @param Repository The repository.
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $this->repository->remove($this->getCriteria());
    }
}
