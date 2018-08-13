<?php
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
declare(strict_types=1);

namespace YomY\ValueObject\Tests;

use YomY\ValueObject\ValueObject;

require_once 'helper/ValueObjectExample.php';
require_once 'helper/ValueObjectExampleLevel2.php';
require_once 'helper/ValueObjectExampleLevel3.php';

class ValueObjectTest extends \PHPUnit\Framework\TestCase {

    /**
     * Provides valid values for a value object
     *
     * @return array
     */
    public static function ValueProvider(): array {
        return [
            [1],
            ['1'],
            ['string with spaces'],
            [true],
            [false],
            [null],
            ['0'],
            [''],
            [\PHP_INT_MAX],
            [\PHP_INT_MIN],
            [(string)\PHP_INT_MAX],
            [(string)\PHP_INT_MIN]
        ];
    }

    /**
     * Provides invalid values for a value object
     *
     * @return array
     */
    public static function InvalidValueProvider(): array {
        return [
            [[]],
            [[1]],
            [(object)[]],
            [(object)[1]]
        ];
    }

    /**
     * Provides comparison values that should evaluate as different
     *
     * @return array
     */
    public static function DifferenceValueProvider(): array {
        return [
            [1, '1'],
            [true, 1],
            [true, '1'],
            [true, 'true'],
            [false, ''],
            [false, 0],
            [false, '0'],
            [false, 'false'],
            [null, '0'],
            [null, 'null'],
            [null, ''],
            [null, false],
        ];
    }

    /**
     * Test creating a value object
     *
     * @dataProvider ValueProvider
     * @param mixed $value
     */
    public function testInstance($value) {
        $object = ValueObject::instance($value);
        $this->assertEquals($value, $object->getValue());
    }

    /**
     * Test if creating a value object with invalid value fails
     *
     * @dataProvider InvalidValueProvider
     * @expectedException \InvalidArgumentException
     * @param mixed $value
     */
    public function testInstanceInvalidValue($value) {
        ValueObject::instance($value);
    }

    /**
     * Test creating a value object from extending class
     *
     * @dataProvider ValueProvider
     * @param mixed $value
     */
    public function testExtendedInstance($value) {
        $object = ValueObjectExample::instance($value);
        $this->assertEquals($value, $object->getValue());
    }

    /**
     * Test creating a value object from extending class fails with invalid value
     *
     * @dataProvider InvalidValueProvider
     * @expectedException \InvalidArgumentException
     * @param mixed $value
     */
    public function testExtendedInstanceInvalidValue($value) {
        ValueObjectExample::instance($value);
    }

    /**
     * Test if multiple calls to instance method with same value produce the same object
     *
     * @dataProvider ValueProvider
     * @param mixed $value
     */
    public function testCompareObjectsSame($value) {
        $object1 = ValueObject::instance($value);
        $object2 = ValueObject::instance($value);
        $this->assertSame($object1, $object2);
    }

    /**
     * Test if multiple calls to instance method with different values produce different objects
     *
     * @dataProvider DifferenceValueProvider
     * @param mixed $value1
     * @param mixed $value2
     */
    public function testCompareObjectsNotSame($value1, $value2) {
        $object1 = ValueObject::instance($value1);
        $object2 = ValueObject::instance($value2);
        $this->assertNotSame($object1, $object2);
    }

    /**
     * Test if instances of different classes with the same value are not the same objects
     */
    public function testCompareObjectsNotSameDifferentClass() {
        $object1 = ValueObject::instance(1);
        $object2 = ValueObjectExample::instance(1);
        $this->assertNotSame($object1, $object2);
    }

    /**
     * Test if instances of different classes with the same value are not the same objects (level 2 class)
     */
    public function testCompareObjectsNotSameDifferentClassLevel2() {
        $object1 = ValueObject::instance(1);
        $object2 = ValueObjectExampleLevel2::instance(1);
        $this->assertNotSame($object1, $object2);
    }

    /**
     * Test if instances of different classes with the same value are not the same objects (level 3 class)
     */
    public function testCompareObjectsNotSameDifferentClassLevel3() {
        $object1 = ValueObject::instance(1);
        $object2 = ValueObjectExampleLevel3::instance(1);
        $this->assertNotSame($object1, $object2);
    }

    /**
     * Test if instances of different classes with the same value are not the same objects (deep level classes)
     */
    public function testCompareObjectsNotSameDifferentClassDeepLevelClasses() {
        $object1 = ValueObjectExampleLevel2::instance(1);
        $object2 = ValueObjectExampleLevel3::instance(1);
        $this->assertNotSame($object1, $object2);
    }

    /**
     * Tests access to non-existing property fails
     *
     * @expectedException \BadFunctionCallException
     */
    public function testPropertyAccessFail() {
        $object = ValueObject::instance(1);
        $object->property;
    }

    /**
     * Tests setting a non-existing property fails
     *
     * @expectedException \BadFunctionCallException
     */
    public function testPropertySetFail() {
        $object = ValueObject::instance(1);
        $object->property = 1;
    }

    /**
     * Tests if isset fails
     *
     * @expectedException \BadFunctionCallException
     */
    public function testPropertyIssetFail() {
        $object = ValueObject::instance(1);
        isset($object->property);
    }

    /**
     * Tests if cloning fails
     *
     * @expectedException \BadFunctionCallException
     */
    public function testCloneFail() {
        $object = ValueObject::instance(1);
        clone $object;
    }


}