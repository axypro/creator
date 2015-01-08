# Format of Context

Each creator (an instance of `axy\creator\Creator`) has own context.
The context explains as the target objects must be created.

The context is an associative array.
All fields are optional.

* `namespace`
* `args`
* `append_args`
* `use_options`
* `classname`
* `creator`
* `parent`
* `validator`
* `modules`
* `moduleResolver`

#### `namespace (string)`

If in the [pointer](pointer.md) specified a short class name then this class is located in the basic namespace.

```php
$creator = new Creator([
    'namespace' => 'my\ns',
]);

$instance1 = $creator('OneClass'); // new \my\ns\OneClass()
$instance2 = $creator('\TwoClass'); // new \TwoClass()
```

By default used the root namespace.
And the short name is equals to the full name.

#### `args (array)` and `append_args (array)`

When call constructor of the target object then `args` append to the head of the arguments list.
And `append_args` to the tail.

```php
$creator = new Creator([
    'args' => [1, 2],
    'append_args' => [3, 4],
]);

$pointer = [
    'classname' => 'MyClass',
    'args' => [5, 6],
];

$instance = $creator($pointer); // new MyClass(1, 2, 5, 6, 3, 4);
```

In the [pointer](pointer.md) you can disabled these arguments for a concrete object.

#### `use_options (boolean)`

Specified as to use an arguments list when short form of pointer is used.

* `FALSE` (by default) - as the arguments list (a numeric array).
* `TRUE` - as the only argument (options). 

See [pointer](pointer.md) for details.

#### `classname (string)` and `creator (callback)`

If pointer do not contains `classname` and `creator` then use this fields.

`classname` - the name of the default class (full name).
If `classname` is not specified then used `creator`.
If `creator` is not specified too then error `InvalidPointer`.

#### `module (array)` or `moduleResolver (callback)`

The options for resolving the `classname`.
See [classname resolving rules](classname.md) for details.

#### `parent (string)`

The full name of the parent class or the interface.
Used to check.

For example all validators must implements `forms\validators\IValidator`.

If `parent` is specified then all created objects will be check by `instanceof $parent`.
If not `instanceof` - error `InvalidPointer`.

#### `validator (callback)`

Additional check if `parent` is not enough.

Takes a created object as only argument.
Returns `TRUE` or `FALSE`.

If `FALSE` - error `InvalidPointer`.
