<?php

declare(strict_types=1);

namespace KHerGe\Arrays;

use ArrayIterator;
use Iterator;

/**
 * Iterates through the values of an array object implementing `ObjectIterable`.
 *
 * This iterator will take a copy of the keys and values of the array object and iterate through those. Multiple
 * iterators may be used for the same array object without affecting the keys and values this iterate will iterate
 * through.
 *
 * ```
 * foreach (new ObjectIterator($arrayObject) as $key => $value) {
 *     // Does not affect iteration.
 *     unset($arrayObject[$key]);
 * }
 * ```
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class ObjectIterator implements Iterator
{
    /**
     * The array object keys.
     *
     * @var ArrayIterator
     */
    private $keys;

    /**
     * The array object values.
     *
     * @var ArrayIterator
     */
    private $values;

    /**
     * Initializes the new array object iterator.
     *
     * @param ObjectIterable $array The array object.
     */
    public function __construct(ObjectIterable $array)
    {
        $this->keys = new ArrayIterator($array->getKeys());
        $this->values = new ArrayIterator($array->getValues());
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->values->current();
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->keys->current();
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->keys->next();
        $this->values->next();
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->keys->rewind();
        $this->values->rewind();
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return $this->keys->valid() && $this->values->valid();
    }
}