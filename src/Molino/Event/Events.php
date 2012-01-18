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

/**
 * Events for the EventMolino.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
final class Events
{
    const CREATE = 'create';
    const PRE_SAVE = 'pre_save';
    const POST_SAVE = 'post_save';
    const PRE_REFRESH = 'pre_refresh';
    const POST_REFRESH = 'post_refresh';
    const PRE_DELETE = 'pre_delete';
    const POST_DELETE = 'post_delete';
    const CREATE_QUERY = 'create_query';
}
