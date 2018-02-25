<?php

declare(strict_types=1);

namespace KHerGe\Arrays;

/**
 * A complex implementation of `ArrayInterface`.
 *
 * An instance of `ComplexArray` behaves like a normal array except that any value type may be used as an array key.
 * Array keys are always compared identically (`===`), making it possible to use multiple instances of the same class
 * as different array keys.
 *
 * There are two significant limitations with PHP array access using objects.
 *
 * 1. It is impossible to tell the difference between `$array[] = 'value';` and `$array[null] = 'value';`. New
 *    instances can be configured to append a new value to the array when a `null` key is provided, or treat `null`
 *    as any other key (preventing appends).
 * 2. Decimal integers in strings will always be converted to an integer type when access via `$array[key]` (e.g.
 *    `$array['123']` is the same as `$array[123]`). It is possible to workaround this issue by invoking the array
 *    access methods directly (e.g. `offsetGet`, `offsetSet`) until a future PHP release addresses this problem.
 *
 * ```
 * $array = new ComplexArray(
 *     [
 *         0,
 *         2,
 *         2.3,
 *         '3',
 *         'example',
 *         true,
 *         [1],
 *         new DateTime(),
 *     ],
 *     [
 *         'is 0',
 *         'is 2',
 *         'is 2.3',
 *         'is "3"',
 *         'is "example"',
 *         'is true',
 *         'is [1]',
 *         'is DateTime',
 *     ]
 * );
 * ```
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class ComplexArray implements ArrayInterface, ObjectIterable
{
    /**
     * The append on `null` flag.
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
     * Initializes the new complex array.
     *
     * @param mixed[] $keys   The array keys.
     * @param mixed[] $values The array values.
     * @param boolean $append Append values when key is `null`?
     *
     * @throws ArrayException If the number of keys and values do not match.
     */
    public function __construct(array $keys = [], array $values = [], bool $append = true)
    {
        if (count($keys) !== count($values)) {
            throw new ArrayException(
                'The number of keys (%d) does not match the number of values (%d).',
                count($keys),
                count($values)
            );
        }

        $this->append = $append;
        $this->keys = $keys;
        $this->values = $values;
    }

    /**
     * {@inheritdoc}
     */
    public function count() : int
    {
        return count($this->values);
    }

    /**
     * {@inheritdoc}
     */
    public function getKeys() : array
    {
        return $this->keys;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator() : ObjectIterator
    {
        return new ObjectIterator($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getValues() : array
    {
        return $this->values;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset) : bool
    {
        return in_array($offset, $this->keys, true);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->values[array_search($offset, $this->keys, true)];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        if ($this->append && ($offset === null)) {
            $this->values[] = $value;

            $key = array_keys($this->values, $value, true);
            $key = array_pop($key);

            $this->keys[] = $key;
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
            unset($this->keys[$index], $this->values[$index]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function serialize() : string
    {
        return serialize([$this->append, $this->keys, $this->values]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($data) : void
    {
        list($this->append, $this->keys, $this->values) = unserialize($data);
    }
}