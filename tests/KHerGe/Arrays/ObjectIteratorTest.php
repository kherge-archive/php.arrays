<?php

declare(strict_types=1);

namespace Tests\KHerGe\Arrays;

use ArrayIterator;
use KHerGe\Arrays\ObjectIterable;
use KHerGe\Arrays\ObjectIterator;
use PHPUnit\Framework\TestCase;

/**
 * Verifies that the array object iterator functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class ObjectIteratorTest extends TestCase
{
    /**
     * Verifies that an array object is iterated properly.
     */
    public function testIterateTheArrayObject()
    {
        // Define our test values.
        $keys = ['alpha', 'beta', 'gamma'];
        $values = [rand(), rand(), rand()];

        // Create an anonymous implementation.
        $array = new class($keys, $values) implements ObjectIterable {
            private $keys;
            private $values;

            public function __construct(array $keys, array $values)
            {
                $this->keys = $keys;
                $this->values = $values;
            }

            public function getKeys() : array
            {
                return $this->keys;
            }

            public function getValues() : array
            {
                return $this->values;
            }
        };

        // Test each iteration.
        $index = 0;

        foreach (new ObjectIterator($array) as $key => $value) {
            self::assertSame($keys[$index], $key, 'The expected key was not returned.');
            self::assertSame($values[$index], $value, 'The expected value was not returned.');

            $index++;
        }

        // Make sure all elements were iterated.
        if ($index !== 3) {
            self::fail('The iterator did not iterate all values.');
        }
    }
}