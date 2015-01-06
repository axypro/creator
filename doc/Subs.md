# The Class `Subs`

There is a class `MyServicesAggregator`, for example.
It is the aggregator of nested services.
 
There is the default configuration of `MyServiceAggregator`. 
 
```php
$defaultConfig = [

    'logger' => [
        'classname' => 'systemLogger',
        'options' => [
            'dir' => null,
        ],
    ],
    
    'template' => [
        'classname' => 'systemTemplate',
        'parent' => 'ITemplate',
        'options' => [
            'syntax' => 'Twig',
            'cache' => false,
        ],
    ],
    
    'db' => [
        'classname' => 'my\DB',
        'options' => [
            'host' => null,
            'name' => null,
        ],
    ],
  
    'request' => [
        'classname' => 'systemRequest',
    ],
];
```

As can be seen, the aggregator contains 4 services: `logger`, `template`, `db`, `request`.
The configuration describe as a service must be created: the default class name and default options.

We want a little edit the configuration to fit your needs.
We will not describe it all as a whole, but only change what we need.

```php
$customConfig = [
    
    'logger' => [
        'options' => [
            'dir' => '/home/my/logs',
        ],
    ],
    
    'template' => 'my\Template',
    
    'db' => false,
];
```

Here:
* We have specified the custom directory for the loader. 
* We have the custom class for the template. It must implement the `ITemplate`. It gets the options from the default config.
* We do not have the database. We disable the `db` service - specified `FALSE`.
* We are completely satisfied with `request`. Do not specify anything. The default `request` will be used.

## `Subs`

The class `axy\creator\Subs` allows you to do this.

```php
use axy\creator\Subs;

$subs = new Subs($defaultConfig, $customConfig);

$subs->logger; // The logger with the custom directory
$subs->template; // The custom template
$subs->request; // The default request
$subs->db; // Exception Disabled
$subs->qwerty; // Exception ServiceNotExists
```

`$defaultConfig` is considered as the dictionary of the [contexts](context.md).
And `$customConfig` is considered as the dictionary of the [pointers](pointer.md).
