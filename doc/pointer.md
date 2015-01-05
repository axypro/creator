# Format of Pointer

The pointer (with the [context](context.md)] describes the created object.

## Full format

Full format is associative array with following optional fields.

* `value`
* `creator`
* `classname`
* `args`
* `options`
* `reset_args`
* Any other fields, which can use the function from `creator` field.

#### `value (mixed)`: the value itself

If this field is specified then it is taken as the target value.
The rest of the field and context are irrelevant.

But this value still must comply with the [context validators](context.md).

#### `creator (callback)`

The function to create an object.
As arguments receives two values:

* The pointer
* The context

```php
$pointer = [
    'creator' => [
        'function' => function ($x, $y, $pointer, $context) {
            return new MyClass();
        },
        'args' => [1, 2],
    ],
];
```

It used [extended callback](https://github.com/axypro/callbacks/blob/master/doc/format.md).
The creator-function will take as arguments 1, 2, the pointer and the context.

#### `classname (string)`

The class name of the target object.

If contain the lead slash then it is the full name (`\my\full\class\Name`).

Else it is short name.
It will be resolved relative to the base (from the context).

If pointer do not contains `value`, `creator` and `classname` then will be used the default algorithm.
In the context will be searched `creator` and `classname`.
If they are not too - the exception `InvalidPointer`.

#### `args (array)`

The list of arguments for the constructor (if specified `classname`).

```php
$pointer = [
    'classname' => '\MyClass',
    'args' => [1, 2],
];

$target = $creator($pointer); // new MyClass(1, 2);
```

If used the default classname (from the context, not from the pointer), `args` is still used.

#### `options (mixed)`

If `args` is not specified and `options` is specified then `options` used as only argument.

```php
$pointer = [
    'classname' => 'MaxLength',
    'options' => [
        'min' => 10,
        'max' => 100,
    ],
];

$creator($pointer); // new MaxLength(['min' => 10, 'max' => 100])
```

#### `reset_args (boolean)`

In the [context](context.md) can be specified `args` and `append_args` which are added to the constructor list arguments.

It can be reset for a concrete object via `reset_args`.

```php
$creator = new Creator([
    'args' => [1, 2, 3],
]);

$creator->create([
    'classname' => 'One', // new One(1, 2, 3, 4, 5)
    'args' => [4, 5],
]);

$creator->create(
    'classname' => 'One', // new One(4, 5)
    'args' => [4, 5],
    'reset_args' => true,
);
```

## `Object`

If pointer is an object this object is target.
 
```php
$instance = new MyClass();

$creator->create($instance); // $instance
```

This is similar to `['value' => $instance]`.

## `String (class name)`

If pointer is a string this is a class name.
This is similar to `['classname' => 'MyClass']`.

## Short format

The short format is the numeric array of two elements.

* The class name
* The arguments list for the constructor.

How to work with the second element is given [context option](context.md) `use_options`.
  
By default it is a list of arguments.
 
```php
$pointer = ['MyClass', [1, 2, 3]];
```

Any value other than the array will result in `InvalidPointer`.

If `use_options` is `TRUE` then the second element is options for only argument of the constructor.

```php
$validators = [
    ['MaxLength', 100],
    ['RegExp', '/@/'],
    ['Between', ['min' => 10, 'max' => 100]],
];
```

If `use_options` is enabled and requires more arguments then use the full format:
```php
$validators = [
    ['MaxLength', 100], // new MaxLength(100)
    [
        'classname' => '\my\Validator', // new \my\Validator(1, 2, 3)
        'args' => [1, 2, 3],
    ],
];
```

## `NULL`

The nested service did not redefine.
Create default service.
See [the class Subs](Subs.md).

## `FALSE`

The nested service is disabled.
See [the class Subs](Subs.md).

## Other

Other value is invalid.

```php
$creator->create(5); // InvalidPointer
```

If you need value (not object) use the full format: 

```php
$creator->create(['value' => 5]); // 5
```
