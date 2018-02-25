<?php

declare(strict_types=1);

namespace KHerGe\Arrays;

use KHerGe\Arrays\ArrayException;
use KHerGe\Arrays\ComplexArray;
use PHPUnit\Framework\TestCase;

/**
 * Verifies that the complex array implementation functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class ComplexArrayTest extends TestCase
{
    /**
     * The array.
     *
     * @var ComplexArray
     */
    private $array;

    /**
     * The array keys.
     *
     * @var mixed[]
     */
    private $keys;

    /**
     * The array values.
     *
     * @var mixed[]
     */
    private $values;

    /**
     * Verify an exception is thrown if keys and values are not balanced.
     */
    public function testUnbalancedKeysAndValuesThrowException()
    {
        $this->expectException(ArrayException::class);
        $this->expectExceptionMessage('The number of keys (1) does not match the number of values (2).');

        new ComplexArray([$this], [$this, $this]);
    }

    /**
     * Verify that the array is counted.
     */
    public function testCount()
    {
        self::assertEquals(5, count($this->array), 'The array was not counted correctly.');
    }

    /**
     * Verify that the array keys are returned.
     */
    public function testGetKeys()
    {
        self::assertSame($this->keys, $this->array->getKeys(), 'The array keys were not returned.');
    }

    /**
     * Verify that the array is iterated.
     */
    public function testIterate()
    {
        $index = 0;

        foreach ($this->array as $key => $value) {
            self::assertSame($this->keys[$index], $key, 'The correct key was not returned.');
            self::assertSame($this->values[$index], $value, 'The correct value was not returned.');

            $index++;
        }

        if ($index !== 5) {
            self::fail('The array was not iterated completely.');
        }
    }

    /**
     * Verify that the array values are returned.
     */
    public function testGetValues()
    {
        self::assertSame($this->values, $this->array->getValues(), 'The array values were not returned.');
    }

    /**
     * Verify that the array is accessible.
     */
    public function testArrayAccess()
    {
        $object = (object) [];

        $this->array[$object] = 123;

        self::assertTrue(isset($this->array[$object]), 'The value should be set.');
        self::assertEquals(123, $this->array[$object], 'The value was not set correctly.');

        unset($this->array[$object]);

        self::assertFalse(isset($this->array[$object]), 'The value should not be set.');
    }

    /**
     * @depends testArrayAccess
     *
     * Verify that values are appended for `null` keys.
     */
    public function testAppendOnNull()
    {
        $array = new ComplexArray([], [], true);
        $array[] = 1;
        $array[] = 2;

        self::assertSame([0, 1], $array->getKeys(), 'The correct keys were not set.');
        self::assertSame([1, 2], $array->getValues(), 'The correct values were not set.');
    }

    /**
     * @depends testArrayAccess
     *
     * Verify that values are replaced for `null` keys.
     */
    public function testReplaceOnNull()
    {
        $array = new ComplexArray([], [], false);
        $array[] = 1;
        $array[] = 2;

        self::assertSame([null], $array->getKeys(), 'The correct keys were not set.');
        self::assertSame([2], $array->getValues(), 'The correct values were not set.');
    }

    /**
     * Verify that the array is serialized and unserialized.
     */
    public function testSerialize()
    {
        $array = unserialize(serialize($this->array));

        self::assertEquals($this->array, $array, 'The array was not serialized or unserialized correctly.');
    }

    /**
     * Creates a new array.
     */
    protected function setUp()
    {
        $this->keys = ['alpha', 1, '', [1], (object) []];
        $this->values = [1, 'beta', 'gamma', 'delta', 'epsilon'];
        $this->array = new ComplexArray($this->keys, $this->values);
    }
}