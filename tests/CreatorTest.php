<?php
/**
 * @package axy\creator
 */

namespace axy\creator\tests;

use axy\creator\Creator;

/**
 * @coversDefaultClass axy\creator\Creator
 */
class CreatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getContext
     */
    public function testConstruct()
    {
        $context = [
            'namespace' => 'basens',
        ];
        $creator = new Creator($context);
        $expected = [
            'namespace' => 'basens',
            'parent' => null,
            'validator' => null,
        ];
        $this->assertEquals($expected, $creator->getContext());
    }

    /**
     * @covers ::__construct
     * @expectedException \axy\creator\errors\InvalidContext
     * @expectedExceptionMessage Creator:Context has an invalid format: "unknown index one, two"
     */
    public function testInvalidContext()
    {
        $context = [
            'namespace' => 'basens',
            'one' => 'one',
            'parent' => 'Parent',
            'two' => 'two',
        ];
        return new Creator($context);
    }

    /**
     * @covers ::create
     * @expectedException \axy\creator\errors\InvalidPointer
     * @expectedExceptionMessage Creator:Pointer has an invalid format: "invalid pointer type"
     */
    public function testInvalidPointer()
    {
        $creator = new Creator();
        $creator->create(5);
    }
}
