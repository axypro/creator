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
            'args' => [1, 2],
        ];
        $creator = new Creator($context);
        $expected = [
            'namespace' => 'basens\\',
            'parent' => null,
            'validator' => null,
            'classname' => null,
            'creator' => null,
            'use_options' => false,
            'args' => [1, 2],
            'append_args' => null,
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

    /**
     * @covers ::create
     * @covers ::__invoke
     */
    public function testDirectly()
    {
        $creator = new Creator();
        $this->assertSame($creator, $creator->create($creator));
        $this->assertSame($creator, $creator($creator));
    }

    /**
     * @covers ::create
     */
    public function testValidationParent()
    {
        $creator = new Creator(['parent' => 'PHPUnit_Framework_TestCase']);
        $this->assertSame($this, $creator->create($this));
        $this->setExpectedException('axy\creator\errors\InvalidPointer');
        $creator->create($creator);
    }

    /**
     * @covers ::create
     */
    public function testValidationValidator()
    {
        $validator = function ($instance) {
            return $instance instanceof Creator;
        };
        $creator = new Creator(['validator' => $validator]);
        $this->assertSame($creator, $creator->create($creator));
        $this->setExpectedException('axy\creator\errors\InvalidPointer');
        $creator->create($this);
    }

    /**
     * @covers ::create
     */
    public function testClassname()
    {
        $creator = new Creator();
        $instance1 = $creator->create('\axy\creator\tests\nstst\Target');
        $this->assertInstanceOf('axy\creator\tests\nstst\Target', $instance1);
        $this->assertEmpty($instance1->args);
        $instance2 = $creator->create('axy\creator\tests\nstst\Target');
        $this->assertInstanceOf('axy\creator\tests\nstst\Target', $instance2);
        $this->assertEmpty($instance2->args);
        $this->setExpectedException('axy\creator\errors\InvalidPointer');
        $creator->create('axy\creator\tests\nstst\Unknown');
    }

    /**
     * @covers ::create
     */
    public function testContextNamespace()
    {
        $creator = new Creator(['namespace' => 'axy\creator\tests']);
        $instance1 = $creator->create('nstst\Target');
        $this->assertInstanceOf('axy\creator\tests\nstst\Target', $instance1);
        $this->assertEmpty($instance1->args);
        $instance2 = $creator->create('\axy\creator\tests\nstst\Target');
        $this->assertInstanceOf('\axy\creator\tests\nstst\Target', $instance2);
        $this->assertEmpty($instance2->args);
        $this->setExpectedException('axy\creator\errors\InvalidPointer');
        $creator->create('axy\creator\tests\nstst\Target');
    }

    /**
     * @covers ::create
     */
    public function testValue()
    {
        $creator = new Creator();
        $this->assertSame(10, $creator->create(['value' => 10]));
        $this->assertSame(null, $creator->create(['value' => null]));
    }
}
