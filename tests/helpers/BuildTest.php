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
     * @param boolean $exception [optional]
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
        return include __DIR__.'/providers/build.php';
    }
}
