<?php
/**
 * @package axy\creator
 */

namespace axy\creator\tests;

use axy\creator\Creator;
use axy\creator\tests\tst\Target;

/**
 * coversDefaultClass axy\creator\Creator
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class CreatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * covers ::__construct
     * covers ::getContext
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
            'append_args' => [],
            'modules' => null,
            'moduleResolver' => null,
        ];
        $this->assertEquals($expected, $creator->getContext());
    }

    /**
     * covers ::__construct
     * @expectedException \axy\creator\errors\InvalidContext
     * @expectedExceptionMessage unknown index one, two
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
     * covers ::create
     * @expectedException \axy\creator\errors\InvalidPointer
     * @expectedExceptionMessage invalid pointer type
     */
    public function testInvalidPointer()
    {
        $creator = new Creator();
        $creator->create(5);
    }

    /**
     * covers ::create
     * covers ::__invoke
     */
    public function testDirectly()
    {
        $creator = new Creator();
        $this->assertSame($creator, $creator->create($creator));
        $this->assertSame($creator, $creator($creator));
    }

    /**
     * covers ::create
     */
    public function testValidationParent()
    {
        $creator = new Creator(['parent' => 'PHPUnit_Framework_TestCase']);
        $this->assertSame($this, $creator->create($this));
        $this->setExpectedException('axy\creator\errors\InvalidPointer');
        $creator->create($creator);
    }

    /**
     * covers ::create
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
     * covers ::create
     */
    public function testClassname()
    {
        $creator = new Creator();
        $instance1 = $creator->create('\axy\creator\tests\tst\Target');
        $this->assertInstanceOf('axy\creator\tests\tst\Target', $instance1);
        $this->assertEmpty($instance1->args);
        $instance2 = $creator->create('axy\creator\tests\tst\Target');
        $this->assertInstanceOf('axy\creator\tests\tst\Target', $instance2);
        $this->assertEmpty($instance2->args);
        $this->setExpectedException('axy\creator\errors\InvalidPointer');
        $creator->create('axy\creator\tests\tst\Unknown');
    }

    /**
     * covers ::create
     */
    public function testContextNamespace()
    {
        $creator = new Creator(['namespace' => 'axy\creator\tests']);
        $instance1 = $creator->create('tst\Target');
        $this->assertInstanceOf('axy\creator\tests\tst\Target', $instance1);
        $this->assertEmpty($instance1->args);
        $instance2 = $creator->create('\axy\creator\tests\tst\Target');
        $this->assertInstanceOf('\axy\creator\tests\tst\Target', $instance2);
        $this->assertEmpty($instance2->args);
        $this->setExpectedException('axy\creator\errors\InvalidPointer');
        $creator->create('axy\creator\tests\tst\Target');
    }

    /**
     * covers ::create
     */
    public function testValue()
    {
        $creator = new Creator();
        $this->assertSame(10, $creator->create(['value' => 10]));
        $this->assertSame(null, $creator->create(['value' => null]));
    }

    /**
     * covers ::create
     */
    public function testCreator()
    {
        $creator = new Creator(['namespace' => 'axy\creator\tests']);
        $pointer = [
            'creator' => function ($pointer, $context) {
                return new Target($context, $pointer);
            }
        ];
        $target = $creator->create($pointer);
        $this->assertInstanceOf('axy\creator\tests\tst\Target', $target);
        $expected = [$creator->getContext(), $pointer];
        $this->assertEquals($expected, $target->args);
    }

    /**
     * covers ::create
     */
    public function testExtendedCreator()
    {
        $creator = new Creator(['namespace' => 'axy\creator\tests']);
        $pointer = [
            'creator' => [
                'function' => function () {
                    return func_get_args();
                },
                'args' => [1, 2],
            ],
        ];
        $expected = [1, 2, $pointer, $creator->getContext()];
        $actual = $creator->create($pointer);
        $this->assertEquals($expected, $actual);
    }

    /**
     * covers ::create
     * @dataProvider providerClass
     * @param array $context
     * @param array $pointer
     * @param array $args
     */
    public function testClass($context, $pointer, $args)
    {
        $creator = new Creator($context);
        if ($args === null) {
            $this->setExpectedException('axy\creator\errors\InvalidPointer');
        }
        $result = $creator->create($pointer);
        $this->assertInstanceOf('axy\creator\tests\tst\Target', $result);
        $this->assertEquals($args, $result->args);
    }

    /**
     * @return array
     */
    public function providerClass()
    {
        return [
            [
                [],
                ['classname' => 'axy\creator\tests\tst\Target'],
                [],
            ],
            [
                ['namespace' => 'axy\creator\tests'],
                ['classname' => 'tst\Target', 'args' => [1, 2]],
                [1, 2],
            ],
            [
                ['namespace' => 'axy\creator\tests'],
                ['classname' => 'axy\creator\tests\tst\Target', 'args' => [1, 2]],
                null,
            ],
            [
                ['namespace' => 'axy\creator\tests', 'args' => [3, 4], 'append_args' => [5, 6]],
                ['classname' => '\axy\creator\tests\tst\Target', 'args' => [1, 2]],
                [3, 4, 1, 2, 5, 6],
            ],
            [
                ['namespace' => 'axy\creator\tests', 'args' => [3, 4], 'append_args' => [5, 6]],
                ['classname' => '\axy\creator\tests\tst\Target', 'options' => ['x' => 1, 'y' => 2]],
                [3, 4, ['x' => 1, 'y' => 2], 5, 6],
            ],
            [
                ['namespace' => 'axy\creator\tests', 'args' => [3, 4], 'append_args' => [5, 6]],
                ['classname' => '\axy\creator\tests\tst\Target'],
                [3, 4, 5, 6],
            ],
        ];
    }

    /**
     * covers ::create
     * @dataProvider providerList
     * @param array $context
     * @param array $pointer
     * @param array $args
     */
    public function testList($context, $pointer, $args)
    {
        $creator = new Creator($context);
        if ($args === null) {
            $this->setExpectedException('axy\creator\errors\InvalidPointer');
        }
        $result = $creator->create($pointer);
        $this->assertInstanceOf('axy\creator\tests\tst\Target', $result);
        $this->assertEquals($args, $result->args);
    }

    /**
     * @return array
     */
    public function providerList()
    {
        return [
            [
                [],
                ['axy\creator\tests\tst\Target'],
                [],
            ],
            [
                ['namespace' => 'axy\creator\tests'],
                ['tst\Target', [1, 2]],
                [1, 2],
            ],
            [
                ['namespace' => 'axy\creator\tests'],
                ['axy\creator\tests\tst\Target', [1, 2]],
                null,
            ],
            [
                ['namespace' => 'axy\creator\tests', 'args' => [3, 4], 'append_args' => [5, 6]],
                ['\axy\creator\tests\tst\Target', [1, 2]],
                [3, 4, 1, 2, 5, 6],
            ],
            [
                ['namespace' => 'axy\creator\tests', 'args' => [3, 4], 'append_args' => [5, 6], 'use_options' => true],
                ['\axy\creator\tests\tst\Target', [1, 2]],
                [3, 4, [1, 2], 5, 6],
            ],
            [
                ['namespace' => 'axy\creator\tests', 'args' => [3, 4], 'append_args' => [5, 6]],
                ['\axy\creator\tests\tst\Target'],
                [3, 4, 5, 6],
            ],
            [
                ['namespace' => 'axy\creator\tests'],
                ['\axy\creator\tests\tst\Target', 5],
                null,
            ],
        ];
    }

    /**
     * covers ::blockCreate
     */
    public function testBlockCreate()
    {
        $context = [
            'namespace' => 'axy\creator\tests',
        ];
        $creator = new Creator($context);
        $block = [
            'this' => $this,
            'five' => ['value' => 5],
            'target' => [
                'classname' => 'tst\Target',
                'args' => [1, 2, 3],
            ],
        ];
        $actual = $creator->blockCreate($block);
        $this->assertInternalType('array', $actual);
        $this->assertArrayHasKey('target', $actual);
        $target = $actual['target'];
        $this->assertInstanceOf('axy\creator\tests\tst\Target', $target);
        $this->assertEquals([1, 2, 3], $target->args);
        $expected = [
            'this' => $this,
            'five' => 5,
            'target' => $target,
        ];
        $this->assertEquals($expected, $actual);
    }

    /**
     * covers ::lazyCreate
     */
    public function testLazy()
    {
        $context = [
            'namespace' => 'axy\creator\tests\tst',
            'args' => [1, 2],
        ];
        $creator = new Creator($context);
        $pointer = ['Target', [3, 4]];
        $count = Target::$count;
        $lazy = $creator->lazyCreate($pointer);
        $this->assertInstanceOf('axy\creator\Lazy', $lazy);
        $this->assertSame($count, Target::$count);
        $target = $lazy();
        $this->assertSame($count + 1, Target::$count);
        $target2 = $lazy();
        $this->assertSame($count + 1, Target::$count);
        $this->assertInstanceOf('axy\creator\tests\tst\Target', $target);
        $this->assertEquals([1, 2, 3, 4], $target->args);
        $this->assertSame($target, $target2);
    }

    public function testModules()
    {
        $context = [
            'modules' => [
                'myModule' => 'axy\creator',
            ],
            'args' => [1, 2],
        ];
        $creator = new Creator($context);
        $pointer = ['myModule:tests\tst\Target', [3, 4]];
        $target = $creator->create($pointer);
        $this->assertInstanceOf('axy\creator\tests\tst\Target', $target);
        $this->assertEquals([1, 2, 3, 4], $target->args);
    }
}
