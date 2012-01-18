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

use Symfony\Component\EventDispatcher\Event as BaseEvent;
use Molino\MolinoInterface;

/**
 * Event.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
class Event extends BaseEvent
{
    private $molino;

    /**
     * Constructor.
     *
     * @param MolinoInterface $molino A molino.
     */
    public function __construct(MolinoInterface $molino)
    {
        $this->molino = $molino;
    }

    /**
     * Returns the molino.
     *
     * @return MolinoInterface The molino.
     */
    public function getMolino()
    {
        return $this->molino;
    }
}
