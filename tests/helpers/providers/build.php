<?php
/**
 * Data provider for BuildTest::testBuild()
 */

namespace axy\creator\tests\helpers\providers;

return [
    [
        ['value' => 1],
        [],
        1,
    ],
    [
        ['value' => $this],
        [],
        $this,
    ],
    [
        ['value' => null],
        [],
        null,
    ],
    [
        [
            'creator' => function ($pointer) {
                return $pointer['x'] * 10;
            },
            'x' => 10,
        ],
        [],
        100,
    ],
    [
        [
            'classname' => 'axy\creator\tests\tst\Target',
            'args' => [1, 2],
        ],
        [],
        [1, 2],
    ],
    [
        [
            'classname' => 'axy\creator\tests\tst\Target',
            'args' => [1, 2],
        ],
        [
            'namespace' => 'axy\creator\tests\\',
        ],
        null,
        true,
    ],
    [
        [
            'classname' => 'tst\Target',
            'args' => [1, 2],
        ],
        [
            'namespace' => 'axy\creator\tests\\',
            'args' => [3, 4],
            'classname' => 'qwe\rty',
        ],
        [3, 4, 1, 2],
    ],
    [
        [
            'classname' => '\axy\creator\tests\tst\Target',
            'options' => [1, 2],
        ],
        [
            'namespace' => 'axy\creator\tests\\',
            'append_args' => [3, 4],
        ],
        [[1, 2], 3, 4],
    ],
    [
        [
            'classname' => '\tst\Target',
        ],
        [
            'namespace' => 'axy\creator\tests\\',
        ],
        null,
        true,
    ],
    [
        [
            'x' => 5,
        ],
        [
            'creator' => function ($pointer) {
                return $pointer['x'] * 3;
            },
        ],
        15,
    ],
    [
        [
            'args' => [1, 2],
        ],
        [
            'namespace' => 'axy\creator\tests\\',
            'classname' => 'axy\creator\tests\tst\Target',
        ],
        [1, 2],
    ],
    [
        [
            'args' => [1, 2],
        ],
        [
            'namespace' => 'axy\creator\tests\\',
        ],
        null,
        true,
    ],
];
