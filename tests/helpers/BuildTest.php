<?php
/**
 * @package axy\creator
 */

namespace axy\creator\tests\helpers;

use axy\creator\helpers\Builder;
use axy\creator\tests\nstst\Target;

/**
 * coversDefaultClass axy\creator\helpers\Builder
 */
class BuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * covers ::build
     * @dataProvider providerBuild
     * @param array $pointer
     * @param array $context
     * @param array $expected
     * @param boolean $exeption [optional]
     */
    public function testBuild($pointer, $context, $expected, $exception = false)
    {
        if ($exception) {
            $this->setExpectedException('axy\creator\errors\InvalidPointer');
        }
        $actual = Builder::build($pointer, $context);
        if ($actual instanceof Target) {
            $this->assertEquals($expected, $actual->args);
        } else {
            $this->assertSame($expected, $actual);
        }
    }

    /**
     * @return array
     */
    public function providerBuild()
    {
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
                    'creator' => function ($pointer, $context) {
                        return $pointer['x'] * 10;
                    },
                    'x' => 10,
                ],
                [],
                100,
            ],
            [
                [
                    'classname' => 'axy\creator\tests\nstst\Target',
                    'args' => [1, 2],
                ],
                [],
                [1, 2],
            ],
            [
                [
                    'classname' => 'axy\creator\tests\nstst\Target',
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
                    'classname' => 'nstst\Target',
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
                    'classname' => '\axy\creator\tests\nstst\Target',
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
                    'classname' => '\nstst\Target',
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
                    'creator' => function ($pointer, $context) {
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
                    'classname' => 'axy\creator\tests\nstst\Target',
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
    }
}
