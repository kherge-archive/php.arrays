<?php

declare(strict_types=1);

namespace KHerGe\Arrays;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use Serializable;

/**
 * Defines how an array object must be implemented.
 *
 * An implementation of `ArrayInterface` allows the object to be accessed like an array. Since PHP's array functions
 * do not support array objects, each implementation also provides its own suite of array methods that either support
 * or replicate the missing functionality.
 *
 * ```
 * $array = new Array();
 * ```
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
interface ArrayInterface extends ArrayAccess, Countable, IteratorAggregate, Serializable
{
}