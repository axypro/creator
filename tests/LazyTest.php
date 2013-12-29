<?php
/**
 * @package axy\creator
 */

namespace axy\creator\tests;

use axy\creator\Lazy;
use axy\creator\tests\nstst\Target;

/**
 * @coversDefaultClass axy\creator\Lazy
 */
class LazyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     * @covers ::__invoke
     */
    public function testLazyCreate()
    {
        $context = [
            'namespace' => 'axy\creator\tests\nstst',
            'args' => [1, 2],
        ];
        $pointer = ['Target', [3, 4]];
        $count = Target::$count;
        $lazy = new Lazy($context, $pointer);
        $this->assertSame($count, Target::$count);
        $target = $lazy();
        $this->assertSame($count + 1, Target::$count);
        $target2 = $lazy();
        $this->assertSame($count + 1, Target::$count);
        $this->assertInstanceOf('axy\creator\tests\nstst\Target', $target);
        $this->assertEquals([1, 2, 3, 4], $target->args);
        $this->assertSame($target, $target2);
    }
}
