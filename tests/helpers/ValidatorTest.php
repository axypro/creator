<?php
/**
 * @package axy\creator
 */

namespace axy\creator\tests\helpers;

use axy\creator\helpers\Validator;
use axy\creator\tests\tst\Target;

/**
 * coversDefaultClass axy\creator\helpers\Validator
 */
class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * covers ::validate
     * @dataProvider providerValidate
     * @param mixed $result
     * @param array $context
     * @param boolean $valid
     */
    public function testValidate($result, $context, $valid)
    {
        if (!$valid) {
            $this->setExpectedException('axy\creator\errors\InvalidPointer');
        }
        Validator::validate($result, $context);
    }

    /**
     * @return array
     */
    public function providerValidate()
    {
        return [
            [
                $this,
                [],
                true,
            ],
            [
                $this,
                [
                    'parent' => 'PHPUnit_Framework_TestCase',
                ],
                true,
            ],
            [
                new Target(),
                [
                    'parent' => 'PHPUnit_Framework_TestCase',
                ],
                false,
            ],
            [
                new Target(1, 2, 3),
                [
                    'validator' => function ($instance) {
                        return (array_sum($instance->args) === 6);
                    },
                ],
                true,
            ],
            [
                new Target(1, 2),
                [
                    'validator' => function ($instance) {
                        return (array_sum($instance->args) === 6);
                    },
                ],
                false,
            ],
            [
                new Target(1, 2, 3),
                [
                    'parent' => 'axy\creator\tests\tst\Target',
                    'validator' => function ($instance) {
                        return (array_sum($instance->args) === 6);
                    },
                ],
                true,
            ],
            [
                new Target(1, 2),
                [
                    'parent' => 'axy\creator\tests\tst\Target',
                    'validator' => function ($instance) {
                        return (array_sum($instance->args) === 6);
                    },
                ],
                false,
            ],
            [
                12,
                [
                    'parent' => 'axy\creator\tests\tst\Target',
                ],
                false,
            ],
        ];
    }
}
