<?php
/**
 * @package axy\creator
 */

namespace axy\creator\tests\helpers;

use axy\creator\helpers\Args;

/**
 * @coversDefaultClass axy\creator\helpers\Args
 */
class ArgsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::createArgs
     * @dataProvider providerCreateArgs
     * @param array $pointer
     * @param array $context
     * @param array $expected (null - exception)
     */
    public function testCreateArgs($pointer, $context, $expected)
    {
        if ($expected === null) {
            $this->setExpectedException('axy\creator\errors\InvalidPointer');
        }
        $actual = Args::createArgs($pointer, $context);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return array
     */
    public function providerCreateArgs()
    {
        return [
            [
                [
                ],
                [
                    'args' => [],
                    'append_args' => [],
                    'use_options' => false,
                ],
                [],
            ],
            [
                [
                    'args' => [1, 2],
                ],
                [
                    'args' => [],
                    'append_args' => [],
                ],
                [1, 2],
            ],
            [
                [
                    'options' => [1, 2],
                ],
                [
                    'args' => [],
                    'append_args' => [],
                ],
                [[1, 2]],
            ],
            [
                [
                    'options' => [1, 2],
                ],
                [
                    'args' => [3, 4],
                    'append_args' => [5, 6],
                ],
                [3, 4, [1, 2], 5, 6],
            ],
            [
                [
                    'args' => [1, 2],
                ],
                [
                    'args' => [3, 4],
                    'append_args' => [5, 6],
                ],
                [3, 4, 1, 2, 5, 6],
            ],
            [
                [
                    'args' => [1, 2],
                    'reset_args' => true,
                ],
                [
                    'args' => [3, 4],
                    'append_args' => [5, 6],
                ],
                [1, 2],
            ],
            [
                [
                ],
                [
                    'args' => [3, 4],
                    'append_args' => [5, 6],
                ],
                [3, 4, 5, 6],
            ],
            [
                [
                    'args' => 3,
                ],
                [
                    'args' => [3, 4],
                    'append_args' => [5, 6],
                ],
                null,
            ],
            [
                [
                    'options' => null,
                ],
                [
                    'args' => [3, 4],
                    'append_args' => [5, 6],
                ],
                [3, 4, null, 5, 6],
            ],
        ];
    }
}
