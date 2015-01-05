# Context and Pointer

Example:

```php
use axy\creator\Creator;

$context = [
    'namespace' => 'forms\validators',
];

$validatorCreator = new Creator($context);

$validators = [
    'NotEmpty',
    'Email',
    ['MaxLength', 100],
    $myValidator,
    '\my\Validators\MyValidator',
];

foreach ($validators as $pointer) {
    $validator = $validatorCreator->create($pointer);
    if (!$validator->isValid()) {
        throw new \LogicException('Is not valid!');
    }
}
```

`$validatorCreator` is a service that creates validators.

`$validators` is an array where each element describes a filter.
This is the array of *pointers*.
 
A *pointer* specified how to create the target object.

* `NotEmpty` - create an instance of class NotEmpty
* `[MaxLength, 100]` - create an instance with specified parameters.
* `$myValidator` - take the prepared object.
* ...

The full pointer format is described [there](pointer.md).

`$context` is *context* of the creator.
It contains information relating to the entire group of objects.

In the example the context specified the basic namespace of the validators classes.
Short class names (such `NotEmpty`) are resolved relative to the basic namespace (`forms\vaildators\NotEmpty`).

The full context format is described [there](context.md).
