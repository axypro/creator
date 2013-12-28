<?php
/**
 * @package axy\creator
 */

namespace axy\creator\tests\helpers;

use axy\creator\helpers\PointerFormat;

/**
 * @coversDefaultClass axy\creator\helpers\PointerFormat
 */
class PointerFormatTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::normalize
     * @dataProvider providerNormalize
     * @param mixed $pointer
     * @param array $expected
     * @param array $context [optional]
     * @param string $exception [optional]
     */
    public function testNormalize($pointer, $expected, $context = null, $exception = null)
    {
        if ($exception) {
            $this->setExpectedException('axy\creator\errors\\'.$exception);
        }
        $actual = PointerFormat::normalize($pointer, $context);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return array
     */
    public function providerNormalize()
    {
        return [
            [
                $this,
                ['value' => $this],
            ],
            [
                '\my\ns\ClassName',
                ['classname' => '\my\ns\ClassName'],
            ],
            [
                ['\my\ns\ClassName'],
                ['classname' => '\my\ns\ClassName'],
            ],
            [
                ['\my\ns\ClassName', [1, 2, 3]],
                ['classname' => '\my\ns\ClassName', 'args' => [1, 2, 3]],
            ],
            [
                ['\my\ns\ClassName', 100],
                ['classname' => '\my\ns\ClassName', 'options' => 100],
                ['use_options' => true],
            ],
            [
                ['\my\ns\ClassName', 100],
                ['classname' => '\my\ns\ClassName', 'options' => 100],
                null,
                'InvalidPointer',
            ],
            [
                false,
                null,
                null,
                'Disabled',
            ],
            [
                null,
                [],
            ],
            [
                3,
                null,
                null,
                'InvalidPointer',
            ],
            [
                ['value' => 3],
                ['value' => 3],
            ],
            [
                [],
                [],
            ],
        ];
    }
}
