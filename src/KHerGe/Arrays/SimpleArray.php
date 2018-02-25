<?php

declare(strict_types=1);

namespace KHerGe\Arrays;

use ArrayIterator;

/**
 * A simple implementation of `ArrayInterface`.
 *
 * An instance of `SimpleArray` effectively behaves just like a regular array. However, due to limitations with how
 * PHP supports array access on objects, it is impossible to tell the difference between `$array[] = 'value';` and
 * `$array[null] = 'value';`. New instances can be configured to append a new value to the array when a `null` key
 * is provided, or treat `null` as any other key (preventing appends).
 *
 * ```
 * $array = new SimpleArray(
 *     [
 *         'alpha' => 1,
 *         'beta' => 2,
 *         'gamma' => 3
 *     ]
 * );
 * ```
 *
 * [PHP array]: https://secure.php.net/manual/en/language.types.array.php
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class SimpleArray implements ArrayInterface
{
    /**
     * The append on `null` flag.
     *
     * @var boolean
     */
    private $append;

    /**
     * The internal array.
     *
     * @var array
     */
    private $array;

    /**
     * Initializes the new simple array.
     *
     * @param array   $array  The initial array values.
     * @param boolean $append Append values when key is `null`?
     */
    public function __construct(array $array = [], bool $append = true)
    {
        $this->append = $append;
        $this->array = $array;
    }

    /**
     * {@inheritdoc}
     */
    public function count() : int
    {
        return count($this->array);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator() : ArrayIterator
    {
        return new ArrayIterator($this->array);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset) : bool
    {
        return array_key_exists(self::mangleKey($offset), $this->array);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->array[self::mangleKey($offset)];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $offset = self::mangleKey($offset);

        if ($offset === null) {
            $this->array[] = $value;
        } else {
            $this->array[$offset] = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->array[self::mangleKey($offset)]);
    }

    /**
     * {@inheritdoc}
     */
    public function serialize() : string
    {
        return serialize($this->array);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($data) : void
    {
        $this->array = unserialize($data);
    }

    /**
     * Mangles an array key to mirror regular array behavior.
     *
     * @param mixed $key The key to mangle.
     *
     * @return mixed The mangled key.
     *
     * @throws ArrayException If the key is not valid.
     */
    private function mangleKey($key)
    {
        if (is_array($key) || is_object($key)) {
            throw new ArrayException('The key type, %s, is not valid.', gettype($key));
        }

        if (!$this->append && ($key === null)) {
            return '';
        } elseif (is_bool($key) || is_float($key)) {
            return (int) $key;
        } elseif (is_string($key) && preg_match('/^-?[1-9][0-9]*$/', $key)) {
            return (int) $key;
        }

        return $key;
    }
}