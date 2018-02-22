<?php

declare(strict_types=1);

namespace KHerGe\Arrays;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use OutOfBoundsException;
use Serializable;

/**
 * Implements an array with support for any kind of key.
 *
 * A new array is created by instantiating this class.
 *
 * ```
 * $array = new AnyKey();
 * ```
 *
 * By default, `null` array keys will append to the end of the array. This can be changed so that `null` keys are
 * treated as actual keys. This change will disable the ability to append to the array without a key. Array access
 * will always require a key to be used.
 *
 * ```
 * $array = new AnyKey(false);
 * ```
 *
 * Array keys are used as is and are strictly matched, there is no type conversion.
 *
 * ```
 * $array[1] = 'This is a different.';
 * $array[1.23] = 'Also a different key.';
 * ```
 *
 * > Unfortunately, string numbers such as `"1"` are still cast as numbers. This is probably a limitation with PHP.
 *
 * Other arrays and objects can be used to access the array.
 *
 * ```
 * $array[[1]] = 'Arrays as keys are supported.';
 * $array[new DateTime()] = 'And so are objects.';
 * ```
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class AnyKey implements ArrayAccess, Countable, IteratorAggregate, Serializable
{
    /**
     * The append flag for `null` keys.
     *
     * @var boolean
     */
    private $append;

    /**
     * The array keys.
     *
     * @var mixed[]
     */
    private $keys = [];

    /**
     * The array values.
     *
     * @var mixed[]
     */
    private $values = [];

    /**
     * Initializes the new array.
     *
     * @param boolean $append Append to array if key is `null`?
     */
    public function __construct(bool $append = true)
    {
        $this->append = $append;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->keys);
    }

    /**
     * Returns all of the keys for the array.
     *
     * @return mixed[] The keys.
     */
    public function getKeys() : array
    {
        return $this->keys;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new AnyKeyIterator($this);
    }

    /**
     * Returns all of the values for the array.
     *
     * @return mixed[] The values.
     */
    public function getValues() : array
    {
        return $this->values;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return in_array($offset, $this->keys, true);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        $index = array_search($offset, $this->keys, true);

        if ($index === false) {
            throw new OutOfBoundsException('Undefined index: ' . var_export($offset, true));
        }

        return $this->values[$index];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        if ($this->append && ($offset === null)) {
            $this->values[] = $value;
            $this->keys[] = array_search($value, $this->values, true);
        } else {
            $index = array_search($offset, $this->keys, true);

            if ($index === false) {
                $this->keys[] = $offset;
                $this->values[] = $value;
            } else {
                $this->values[$index] = $value;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        $index = array_search($offset, $this->keys, true);

        if ($index !== false) {
            unset($this->keys[$index]);
            unset($this->values[$index]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([$this->keys, $this->values]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($data)
    {
        list($this->keys, $this->values) = unserialize($data);
    }
}
