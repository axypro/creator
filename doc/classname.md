# Resolving Class Name

If a [pointer](pointer.md) point that a target object should be created by a `classname` then to happen resolving `classname` to the full class name.

These rules apply to [pointer](pointer.md).
The [context](context.md) option `classname` must contain the full class name.

One. If `classname` contains `\` as first character (`\the\full\class\Name`) this is the target class name.

```php
$context = [
    'namespace' => 'base\ns',
];

$pointer = '\full\Name'; // \full\Name
```

Two. If `classname` does not begin with a slash and does not contain a colon it is a relative class name.
It resolved by a basic namespace from the [context](context.md).

```php
$context = [
    'namespace' => 'base\ns',
];

$pointer = 'short\Name'; // \base\ns\short\Name
```

By default, the basic namespace corresponds the root namespace.
In this case short name is equal to full name.

```php
$context = [];

$pointer = 'short\Name'; // \short\Name
```

Three. `classname` must contains a module name (as `module:relativeName`).
A module has its own namespace.

A module resolving occurs via options `modules` and `moduleResolver` from [context](context.md).

```php
$context = [
    'namespace' => 'base\ns',
    'modules' => [
        'my.module' => 'my\module\validators',
        'other.module' => 'other\module\validators',
    ],
    'moduleResolver' => function ($module, $shortName) {
        if ($module === 'qwerty') {
            return 'qwe\rty\'.$shortName;
        }
    },
];

$validatorsPointers = [
    'NotEmpty',          // base\ns\NotEmpty (short name)
    '\my\MyValidator',   // my\MyValidator (full name)
    'my.module:Custom',  // my\module\validators\Custom (module)
    'qwerty:Qwerty',     // qwe\rty\Qwerty (other module)
    'unkn:Name',         // Error: unknown module
    $myValidator,        // the object directly
];
```

A module is searched in `modules` (if it is defined).
It then calls `moduleResolver` (if it is defined).

* `moduleResolver()` gets a module name and a short class name.
* It should return the full class name.
* If it returns not string this mean that the module is not exist.
