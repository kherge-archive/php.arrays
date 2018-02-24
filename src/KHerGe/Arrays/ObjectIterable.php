<?php

declare(strict_types=1);

namespace KHerGe\Arrays;

use ArrayAccess;

/**
 * Defines how an iterable array object must be implemented.
 *
 * An iterable array object provides a `getKeys` and `getValues` method. In theory, you should be able to use the
 * `array_combine()` function with the returned values to produce the original array. An array object iterator will
 * retrieve the keys and values from an object implementing this interface and iterate through them.
 *
 * @author Kevin Herrera <kevin@herrera.io>s
 */
interface ObjectIterable extends ArrayAccess
{
    /**
     * Returns the keys in the array object.
     *
     * @return mixed[] The keys.
     */
    public function getKeys() : array;

    /**
     * Returns the values in the array object.
     *
     * @return mixed[] The values.
     */
    public function getValues() : array;
}