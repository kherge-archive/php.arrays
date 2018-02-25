<?php

declare(strict_types=1);

namespace Tests\KHerGe\Arrays;

use KHerGe\Arrays\ArrayException;
use KHerGe\Arrays\SimpleArray;
use PHPUnit\Framework\TestCase;

/**
 * Verifies that the simple array implementation functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class SimpleArrayTest extends TestCase
{
    /**
     * The array.
     *
     * @var SimpleArray
     */
    private $array;

    /**
     * The initial values.
     *
     * @var array
     */
    private $values = ['alpha' => 1, 1 => 'beta', '' => 'gamma'];

    /**
     * Returns the keys that will thrown an exception.
     *
     * @return array The array keys.
     */
    public function getKeysToException()
    {
        return [
            [[1]],
            [$this],
        ];
    }

    /**
     * Returns the managled keys and expected keys.
     *
     * @return array The array keys.
     */
    public function getKeysToMangle()
    {
        return [
            ['', null, false],
            [0, null, true],
            [0, false, true],
            [1, true, true],
            [2, 2.3, true],
            [3, '3', true],
            [-4, '-4', true],
            ['01', '01', true],
            ['-05', '-05', true],
            ['+6', '+6', true],
            ['0.2', '0.2', true],
            ['-0.2', '-0.2', true],
        ];
    }

    /**
     * Verify that the array is counted.
     */
    public function testCount()
    {
        self::assertEquals(3, count($this->values), 'The array was not counted properly.');
    }

    /**
     * Verify that the array is iterated.
     */
    public function testIterate()
    {
        $keys = array_keys($this->values);
        $index = 0;
        $values = array_values($this->values);

        foreach ($this->array as $key => $value) {
            self::assertSame($keys[$index], $key, 'The correct key was not returned.');
            self::assertSame($values[$index], $value, 'The correct value was not returned.');

            $index++;
        }

        if ($index !== 3) {
            self::fail('Not all of the array was iterated.');
        }
    }

    /**
     * Verify that the array is accessible.
     */
    public function testArrayAccess()
    {
        $this->array['test'] = 123;

        self::assertTrue(isset($this->array['test']), 'The value should be set.');
        self::assertEquals(123, $this->array['test'], 'The value was not set correctly.');

        unset($this->array['test']);

        self::assertFalse(isset($this->array['test']), 'The value should not be set.');
    }

    /**
     * @depends testArrayAccess
     *
     * Verify that certain keys throw an exception.
     *
     * @dataProvider getKeysToException
     *
     * @param mixed $key The array key to use.
     */
    public function testExceptionKeys($key)
    {
        $this->expectException(ArrayException::class);
        $this->expectExceptionMessage(sprintf('The key type, %s, is not valid.', gettype($key)));

        $this->array[$key] = true;
    }

    /**
     * @depends testArrayAccess
     * @depends testIterate
     *
     * Verify that keys are mangled like normal PHP arrays.
     *
     * @dataProvider getKeysToMangle
     *
     * @param mixed   $expected The expected array key.
     * @param mixed   $key      The array key to use.
     * @param boolean $append   Append on `null` key?
     */
    public function testMangleKeys($expected, $key, $append)
    {
        $array = new SimpleArray([], $append);
        $array[$key] = true;

        foreach ($array as $k => $v) {
            self::assertSame($expected, $k, 'The key was not mangled correctly.');
        }

        if (!isset($k)) {
            self::fail('The array was not iterated.');
        }
    }

    /**
     * @depends testArrayAccess
     * @depends testIterate
     *
     * Verify that the array is serialized and unserialized.
     */
    public function testSerialize()
    {
        $array = unserialize(serialize($this->array));
        $normal = [];

        foreach ($array as $key => $value) {
            $normal[$key] = $value;
        }

        self::assertEquals($this->values, $normal, 'The array was not serialized or unserialized correctly.');
    }

    /**
     * Creates a new array with initial values.
     */
    protected function setUp()
    {
        $this->array = new SimpleArray($this->values);
    }
}