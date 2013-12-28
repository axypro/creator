<?php
/**
 * @package axy\creator
 */

namespace axy\creator\tests\helpers;

use axy\creator\helpers\ContextFormat;

/**
 * @coversDefaultClass axy\creator\helpers\ContextFormat
 */
class ContextFormatTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::normalize
     * @dataProvider providerNormalize
     * @param mixed $pointer
     * @param array $expected (null - exception)
     */
    public function testNormalize($context, $expected = null)
    {
        if (!$expected) {
            $this->setExpectedException('axy\creator\errors\InvalidContext');
        }
        $actual = ContextFormat::normalize($context);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return array
     */
    public function providerNormalize()
    {
        return [
            [
                [],
                [
                    'namespace' => '\\',
                    'args' => [],
                    'append_args' => [],
                    'use_options' => false,
                    'classname' => null,
                    'creator' => null,
                    'parent' => null,
                    'validator' => null,
                ],
            ],
            [
                [
                    'namespace' => 'my\ns',
                    'args' => [1, 2],
                    'use_options' => true,
                    'classname' => null,
                ],
                [
                    'namespace' => '\my\ns\\',
                    'args' => [1, 2],
                    'append_args' => [],
                    'use_options' => true,
                    'classname' => null,
                    'creator' => null,
                    'parent' => null,
                    'validator' => null,
                ],
            ],
            [
                [
                    'namespace' => '\my\ns\\',
                    'args' => [1, 2],
                    'use_options' => true,
                    'classname' => null,
                ],
                [
                    'namespace' => '\my\ns\\',
                    'args' => [1, 2],
                    'append_args' => [],
                    'use_options' => true,
                    'classname' => null,
                    'creator' => null,
                    'parent' => null,
                    'validator' => null,
                ],
            ],
            [
                [
                    'namespace' => '\\',
                    'args' => [1, 2],
                    'use_options' => false,
                ],
                [
                    'namespace' => '\\',
                    'args' => [1, 2],
                    'append_args' => [],
                    'use_options' => false,
                    'classname' => null,
                    'creator' => null,
                    'parent' => null,
                    'validator' => null,
                ],
            ],
            [
                [
                    'namespace' => 'my\ns',
                    'args' => [1, 2],
                    'x' => 1,
                    'y' => 2,
                ],
                null,
            ],
        ];
    }
}
