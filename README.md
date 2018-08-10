# valueobject
Value Objects and Enums

[![Build Status](https://travis-ci.org/yomy/valueobject.svg?branch=master)](https://travis-ci.org/yomy/valueobject)

## Installation and documentation

- Available as [Composer] package [yomy/valueobject].

## What is this library about

This library adds a ValueObject and EnumValueObject classes.

## Examples of value object
Creating an object:
```php
use YomY\ValueObject\ValueObject;
$object = ValueObject::instance(1);
```

Getting value from the object
```php
$value = $object->getValue();
```

Objects with the same value are going to be the same object

```php
$object1 = ValueObject::instance(1);
$object2 = ValueObject::instance(1);
//These two are the same objects ($object1 === $object2)
```

You can use type hinting in methods
```php
public function doSomething(ValueObject $valueObject) {
    $value = $valueObject->getValue();
    ...
}
```

You can extend the object for more detailed type hinting
```php
class UserId extends ValueObject {}
class DataId extends ValueObject {}
...
public function doSomething(UserId $userId, DataId $dataId) {
    ...
}
```

Objects of different class or variable type are different 
```php
$object1 = ValueObject::instance('');
$object2 = ValueObject::instance(null);
$object3 = ValueObject::instance(false);
$object4 = ExtendedValueObject::instance('');
$object5 = ExtendedValueObject::instance(null);
$object6 = ExtendedValueObject::instance(false);
//All of the above are different
```

## Examples of Enum value object

Creating an enum object
```php
use YomY\ValueObject\EnumValueObject;
class Category extends EnumValueObject {
    const FIRST = 1;
    const SECOND = 2;
    const THIRD = 3;
}
```

Creating enum objects
```php
$category = Category::instance(Category::FIRST);
```
or by referring referring to key
```php
$category = Category::FIRST();
```

You will get an error if you try to instantiate invalid value
```php
$category = Category::instance('missing_value');
$category = Category::MISSING();
```

## Examples of Positive Integer value object
As value objects are commonly used as identifiers for database entities 
with an integer key, positive int value object ensures a valid key object for this purpose

Creating an object:
```php
use YomY\ValueObject\PositiveIntValueObject;
$object1 = PositiveIntValueObject::instance(1);
$object2 = PositiveIntValueObject::instance('1');
//These two are the same objects ($object1 === $object2)
```

Usually, the id key in the db cannot be a 0, so these objects are invalid:
```php
$object = PositiveIntValueObject::instance(0);
$object = PositiveIntValueObject::instance('0');
```

Of course, as with a basic value object, it is intended to use these
in extended classes.
```php
class UserId extends PositiveIntValueObject {}
class DataId extends PositiveIntValueObject {}
$user = UserId::instance(42);
$data = DataId::instance(42);
//these two are not the same
```