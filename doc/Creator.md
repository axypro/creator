# Class `Creator`

```php
use axy\creator\Creator;

$creator = new Creator($context);
$object1 = $creator($pointer1);
$object2 = $creator($pointer2);
```

#### `__construct([array $context])`

* `$context`: the [context](context.md) of the creator. Empty array by default.

#### `create(mixed $pointer):mixed`

* `$pointer`: the [pointer](pointer.md) of the target object.

Returns the object that specified the pointer.
Can throw the exception `InvalidPointer`.

#### `__invoke(mixed $pointer):mixed`

The following codes are similar:

* `$creator->create($pointer)`
* `$creator($pointer)`

#### `blockCreate(array $block):array`

* `$block`: the list of the pointers

Returns the list of the target objects.

```php
$validatorsPointers => [
    'NotEmpty',
    'Email',
    ['MaxLength', 100],
    $myValidator,
    '\my\Validators\MyValidator',
];

$creator = new Creator($contextValidators);

$validators = $creator->blockCreate($validatorsPointers);

foreach ($validators as $validator) {
    if (!$validator->isValid($value)) {
        throw new \LogicException('Is not valid!');
    }
}
```

#### `lazyCreate(mixed $pointer):Lazy`

Create an instance of [the Lazy class](Lazy.md)

#### `getContext():array`

Returns the normalized context of the creator.
