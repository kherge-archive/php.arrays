<?php

declare(strict_types=1);

namespace Tests\KHerGe\Arrays;

use KHerGe\Arrays\AnyKey;
use KHerGe\Arrays\AnyKeyIterator;
use PHPUnit\Framework\TestCase;

/**
 * Verifies that the any key array iterator functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class AnyKeyIteratorTest extends TestCase
{
    /**
     * Verify that the array is iterated.
     */
    public function testIterateArray()
    {
        $array = new AnyKey();
        $array[0] = 'integer';
        $array[0.1] = 'float';
        $array[[]] = 'array';
        $array[$this] = 'object';

        $count = 0;
        $keys = $array->getKeys();
        $values = $array->getValues();

        foreach (new AnyKeyIterator($array) as $key => $value) {
            self::assertSame($keys[$count], $key, 'The correct key was not returend.');
            self::assertSame($values[$count], $value, 'The correct value was not returned.');

            $count++;
        }
    }
}