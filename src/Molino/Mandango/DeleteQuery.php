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

/**
 * The delete query for Mandango.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
class DeleteQuery extends BaseQuery implements DeleteQueryInterface
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $this
            ->getMolino()
            ->getMandango()
            ->getRepository($this->getModelClass())
            ->remove($this->getCriteria())
        ;
    }
}
