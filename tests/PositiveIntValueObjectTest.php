<?php
declare(strict_types=1);
/**
 * Copyright 2018 Milos Jovanovic <email.yomy@gmail.com>
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *     http://www.apache.org/licenses/LICENSE-2.0
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace YomY\ValueObject\Tests;

use YomY\ValueObject\PositiveIntValueObject;

require_once 'helper/PositiveIntValueObjectExample.php';

class PositiveIntValueObjectTest extends \PHPUnit\Framework\TestCase {

    /**
     * Provides valid values for a positive int value object
     *
     * @return array
     */
    public static function ValueProvider(): array {
        return [
            [1],
            [\PHP_INT_MAX],
            ['1'],
            [(string)\PHP_INT_MAX]
        ];
    }

    /**
     * Provides invalid values for a positive int value object
     *
     * @return array
     */
    public static function InvalidValueProvider(): array {
        return [
            [0],
            [-1],
            ['0'],
            ['-1'],
            ['1.1'],
            ['string'],
            [\PHP_INT_MIN],
            [null]
        ];
    }


    /**
     * Provides comparison values that should evaluate as same
     *
     * @return array
     */
    public static function SameInstanceValueProvider(): array {
        return [
            [1, '1'],
            ['1', 1],
            [\PHP_INT_MAX, (string)\PHP_INT_MAX],
            [(string)\PHP_INT_MAX, \PHP_INT_MAX]
        ];
    }

    /**
     * Test creating a positive int value object
     *
     * @dataProvider ValueProvider
     * @param mixed $value
     */
    public function testInstance($value) {
        $object = PositiveIntValueObject::instance($value);
        $this->assertEquals($value, $object->getValue());
    }

    /**
     * Test if creating a positive int value object with invalid value fails
     *
     * @dataProvider InvalidValueProvider
     * @expectedException \InvalidArgumentException
     * @param mixed $value
     */
    public function testInstanceInvalidValue($value) {
        PositiveIntValueObject::instance($value);
    }

    /**
     * Test if positive int value objects are same
     *
     * @dataProvider SameInstanceValueProvider
     * @param mixed $value1
     * @param mixed $value2
     */
    public function testCompareObjectsSame($value1, $value2) {
        $object1 = PositiveIntValueObject::instance($value1);
        $object2 = PositiveIntValueObject::instance($value2);
        self::assertSame($object1, $object2);
    }

    /**
     * Test if positive int instances of different classes with the same value are not the same objects
     */
    public function testCompareObjectsNotSameDifferentClass() {
        $object1 = PositiveIntValueObject::instance(1);
        $object2 = PositiveIntValueObjectExample::instance(1);
        $this->assertNotSame($object1, $object2);
    }

    /**
     * Test if positive int value object is always holding an int value
     *
     * @dataProvider ValueProvider
     * @param mixed $value
     */
    public function testAlwaysInt($value) {
        $object = PositiveIntValueObject::instance($value);
        $this->assertSame((int)$value, $object->getValue());
    }

}