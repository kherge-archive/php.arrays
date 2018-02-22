<?php

declare(strict_types=1);

namespace KHerGe\Arrays;

use ArrayIterator;
use Iterator;

/**
 * Implements an iterator for the `AnyKey` array.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class AnyKeyIterator implements Iterator
{
    /**
     * The array.
     *
     * @var AnyKey
     */
    private $array;

    /**
     * The array key iterator.
     *
     * @var ArrayIterator
     */
    private $keys;

    /**
     * Initializes the new iterator.
     *
     * @param AnyKey $array The array.
     */
    public function __construct(AnyKey $array)
    {
        $this->array = $array;
        $this->keys = new ArrayIterator($array->getKeys());
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->array[$this->keys->current()];
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
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->keys->rewind();
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return $this->keys->valid();
    }
}