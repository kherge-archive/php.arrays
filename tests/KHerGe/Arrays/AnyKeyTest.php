<?php

declare(strict_types=1);

namespace Tests\KHerGe\Arrays;

use DateTime;
use IteratorAggregate;
use KHerGe\Arrays\AnyKey;
use KHerGe\Arrays\AnyKeyIterator;
use PHPUnit\Framework\TestCase;

/**
 * Verifies that the any key array functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class AnyKeyTest extends TestCase
{
    /**
     * The any key array.
     *
     * @var AnyKey
     */
    private $array;

    /**
     * Verify that an array can be accessed using an object.
     */
    public function testAccessUsingObjectKey()
    {
        $this->array[$this] = 'test';

        self::assertTrue(isset($this->array[$this]), 'The key must exist.');
        self::assertEquals('test', $this->array[$this], 'The value must be set.');

        unset($this->array[$this]);

        self::assertFalse(isset($this->array[$this]), 'The key must not exist.');
    }

    /**
     * Verify that an array can be accessed using an normal key.
     */
    public function testAccessUsingNormalKey()
    {
        $this->array['this'] = 'test';

        self::assertTrue(isset($this->array['this']), 'The key must exist.');
        self::assertEquals('test', $this->array['this'], 'The value must be set.');

        unset($this->array['this']);

        self::assertFalse(isset($this->array['this']), 'The key must not exist.');
    }

    /**
     * Verify that an array can be accessed after appending a value.
     */
    public function testAccessAfterAppend()
    {
        $this->array[] = 'test';

        self::assertTrue(isset($this->array[10]), 'The key must exist.');
        self::assertEquals('test', $this->array[10], 'The value must be set.');

        unset($this->array[10]);

        self::assertFalse(isset($this->array[10]), 'The key must not exist.');
    }

    /**
     * Verify that `null` keys are also used as an actual key.
     */
    public function testAccessNullAsKey()
    {
        $array = new AnyKey(false);

        $array[] = 'test';
        $array[] = 'changed';

        self::assertFalse(isset($array[10]), 'The value must not be appended.');
        self::assertTrue(isset($array[null]), 'The key must exist.');
        self::assertEquals('changed', $array[null], 'The value must be replaced.');

        unset($array[null]);

        self::assertFalse(isset($array[null]), 'The key must not exist.');
    }

    /**
     * @depends testAccessAfterAppend
     *
     * Verify that the array is counted.
     */
    public function testCountValues()
    {
        self::assertEquals(0, count($this->array), 'The array must be empty.');

        $this->array[] = 'test';

        self::assertEquals(1, count($this->array), 'The array must have one value.');
    }

    /**
     * Verify that an iterator is returned for the array.
     */
    public function testGetIterator()
    {
        self::assertInstanceOf(IteratorAggregate::class, $this->array, 'An iterator must be implemented.');
        self::assertInstanceOf(
            AnyKeyIterator::class,
            $this->array->getIterator(),
            'The any key iterator must be returned.'
        );
    }

    /**
     * Verify that the array is serialized and unserialized.
     */
    public function testSerialize()
    {
        $this->array[new DateTime('2018-01-22 06:43:22')] = 'test';

        $array = unserialize(serialize($this->array));

        self::assertEquals(
            '2018-01-22 06:43:22',
            $array->getKeys()[10]->format('Y-m-d H:i:s'),
            'The array was not serialized properly.'
        );

        self::assertEquals('test', $array->getValues()[10], 'The array was not serialized properly.');
    }

    /**
     * Creates a new instance of the any key array.
     */
    protected function setUp()
    {
        $this->array = new AnyKey();

        // Mess with the array.
        for ($i = 0; $i < 10; $i++) {
            $this->array[$i] = rand();

            unset($this->array[$i]);
        }
    }
}