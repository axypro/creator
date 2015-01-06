# The `Lazy` class

An instance of `axy\creator\Lazy` is lazy builder of the target object.
The object will be created on the first request.
Once.
Or will not be created if you do not need it.

```php
$context = [
    'namespace' => 'my\ns',
];

$pointer = ['MyClass', ['arg1', 'arg2']];

$lazy = new Lazy($context, $pointer);

// ...

$object1 = $lazy(); // new \my\ns\MyClass('arg1', 'arg2');
  
// ...

$object2 = $lazy(); // returns the object from the cache
($object2 === $object1);
```

There are two way to create:

```php
$lazy1 = new Lazy($creator, $pointer);
$lazy2 = $creator->lazyCreate($pointer);
```

#### `__construct(mixed $creator, mixed $pointer)`

* `$creator`: the instance of the [Creator](Creator.md) or the [context](context.md)
* `$pointer`: the [pointer](pointer.md)

#### `__invoke():mixed`

Only method.
In the first call creates an object.
On subsequent calls returns it from the cache.

Can throw the exceptions `InvalidPointer`, `Disabled` and `InvalidContext`.
