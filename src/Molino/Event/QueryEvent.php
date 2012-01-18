<?php

/*
 * This file is part of the Molino package.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Molino\Event;

use Molino\MolinoInterface;
use Molino\QueryInterface;

/**
 * QueryEvent.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
class QueryEvent extends Event
{
    private $query;

    /**
     * Constructor.
     *
     * @param MolinoInterface $molino A molino.
     * @param QueryInterface  $query  A query.
     */
    public function __construct(MolinoInterface $molino, QueryInterface $query)
    {
        parent::__construct($molino);

        $this->query = $query;
    }

    /**
     * Returns the query.
     *
     * @return QueryInterface The query.
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Sets the query.
     *
     * @param QueryInterface $query A query.
     */
    public function setQuery(QueryInterface $query)
    {
        $this->query = $query;
    }
}
