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

namespace YomY\ValueObject;

/**
 * Class ValueObject
 *
 * @package YomY\ValueObject
 */
class ValueObject implements ValueObjectInterface {

    /**
     * @var ValueObjectInterface[]
     */
    protected static $instances;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var string
     */
    private $valueType;

    /**
     * @param mixed $value
     */
    private function __construct($value) {
        $this->value = $value;
        $this->valueType = \gettype($value)[0];
    }

    /**
     * @param mixed $value
     * @return ValueObjectInterface
     * @throws \InvalidArgumentException
     */
    public static function instance($value): ValueObjectInterface {
        static::validateValue($value);
        return static::makeInstance(static::class, $value);
    }

    /**
     * Validates if a value is valid, throws InvalidArgumentException if not
     *
     * @param mixed $value
     * @throws \InvalidArgumentException
     */
    protected static function validateValue($value) {
        if (\is_array($value) || \is_object($value)) {
            throw new \InvalidArgumentException('Invalid value');
        }
    }

    /**
     * Creates an array key for given storage value by prefixing the string evaluation with a value type
     *
     * @param string $class
     * @param mixed $value
     * @return string
     */
    private static function getStorageKey(string $class, $value): string {
        return $class . '|' . \gettype($value)[0] . '|' . $value;
    }

    /**
     * @param string $class
     * @param mixed $value
     * @return ValueObjectInterface
     */
    protected static function makeInstance(string $class, $value): ValueObjectInterface {
        $storageKey = static::getStorageKey($class, $value);
        if (!isset(static::$instances[$storageKey])) {
            static::$instances[$storageKey] = new $class($value);
        }
        return static::$instances[$storageKey];
    }

    /**
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string {
        return (string)$this->getValue();
    }


    /**
     * @param string $name
     * @throws \BadFunctionCallException
     */
    public function __get($name) {
        throw new \BadFunctionCallException($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @throws \BadFunctionCallException
     */
    public function __set($name, $value) {
        throw new \BadFunctionCallException($name . ' -> ' . $value);
    }

    /**
     * @param string $name
     * @throws \BadFunctionCallException
     */
    public function __isset($name) {
        throw new \BadFunctionCallException($name);
    }

    /**
     * Prevents mutability
     *
     * @throws \BadFunctionCallException
     */
    public function __clone() {
        throw new \BadFunctionCallException('Cloning not allowed');
    }

}