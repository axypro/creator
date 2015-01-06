# List of Errors

All errors of the library are located in the namespace `axy\magic\creator`.

#### `InvalidPointer`

A pointer has invalid format.

```php
$creator->create(5); // 5 - is not pointer
```

Or points to unknown object.

```php
$creator->create('UndefinedClass'); // UndefinedClass is undefined
```

#### `InvalidContext`

Format of the context is invalid.

```php
$context = [
    'namespace' => 'my\ns',
    'qwerty' => 5,
];

$creator = new Creator($context); // unknown index qwerty
```

#### `Disabled`

A service is disabled.
See the [Subs class](Subs.md)

#### `ServiceNotExists`

Tried access to an indefinite service.
