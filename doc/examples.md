# Examples of use

## 1. List of similar objects

For example, there is a form and a list of the form fields.
Every field has an associated list of filters and validators.
Each filter or validator corresponds to an object with a basic interface.

Create each time all the necessary objects for each field of each form

* It is boring.
* And overhead. They can be not necessary.

Simply describe the form of a config and there to describe its parameters.
Such as validators:

```php
'validators' => [
    'NotEmpty',
    'Email',
    ['MaxLength', 100],
    $myValidator,
    '\my\Validators\MyValidator',
];
```

Here `NotEmpty`, `Email`, `MaxLength` are "standard" validators.
In fact, it is just the class names in a namespace (basic validators namespace).

For custom validator can be using a full name (with the lead slash) - `"\my\Validators\MyValidator"`.
Or you can send an object-validator directly (`$myValidator).

If a validator require the parameters (`MaxLength` require the maximum length) then use form `[Class name, parameters]`.

Example for `axy\creator`:

```php
use axy\creator\Creator;

function validate($value, array $validators) 
{
    $creator = new Creator([
        'namespace' => 'forms\validators', // the basic namespace for "standard" validators
        'parent' => 'forms\validators\IValidator', // basic class or interface (for additional check)
        'use_options' => true,
    ]);

    foreach ($validators as $item) {
        if (!$creator->create($item)->isValid($value)) {
            return false;
        }
    }

    return true; // All validators are passed
}
```

## 2. Nested services

There is a service locator.
It has a list of nested services.

For example, simple application object:

```php
/**
 * @property-read \services\Config $config
 * @property-read \services\Router $router
 * @property-read \services\DB $db
 * ...
 */
class App
{
}

// Example of using:
$app->db->query('DROP DATABASE `main`');
```

You need create nested services.

```php
public function __construct()
{
    $this->db = new \services\DB($this);
    $this->router = new \services\Router($this);
    $this->config = new \services\Config($this);
}

public function __get($key)
{
    switch ($key) {
        case 'db': return $this->db;
        case 'router': return $this->router;
        case 'config': return $this->config;
    }
}
```

This is 

* very boring
* not lazy
* Required implementation of `__get()`. We do not want that services will be overwritten.

With `axy\creator`:

```php
use axy\creator\Creator;

class App
{
    public function __construct()
    {
        $this->creator = new Creator([
            'namespace' => 'services',
            'args' => [$this],
        ]);
    }
    
    public function __get($key)
    {
        if (!isset($this->services[$key])) {
            throw new \LogicException('Service not found');
        }
        $service = $this->service[$key];
        if (is_string($service)) {
            $service = $this->creator->create($service);
            $this->services[$key] = $service;
        }
        return $service;
    }
    
    public function __isset($key)
    {
        return isset($this->services[$key]);
    }
    
    private $services = [
        'db' => 'DB',
        'router' => 'Router',
        'config' => 'Config',
    ];
    
    private $creator;
}
```

Specify only the name of the service and its class.

You can make a base class and leave only the service list in locators.

```php
class App extends ServiceLocator
{
    protected $services = [
        'db' => 'DB',
        'router' => 'Router',
        'config' => 'Config',
    ];
}
```
