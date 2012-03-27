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
    const CREATE = 'molino_create';
    const PRE_SAVE = 'molino_pre_save';
    const POST_SAVE = 'molino_post_save';
    const PRE_REFRESH = 'molino_pre_refresh';
    const POST_REFRESH = 'molino_post_refresh';
    const PRE_DELETE = 'molino_pre_delete';
    const POST_DELETE = 'molino_post_delete';
    const CREATE_QUERY = 'molino_create_query';
}
