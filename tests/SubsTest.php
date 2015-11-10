<?php
/**
 * @package axy\creator
 */

namespace axy\creator\tests;

use axy\creator\Subs;
use axy\creator\errors\ServiceNotExists;

/**
 * coversDefaultClass axy\creator\Subs
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class SubsTest extends \PHPUnit_Framework_TestCase
{
    private $testContexts = [
        'one' => [
            'classname' => 'axy\creator\tests\tst\Target',
            'args' => [1, 2],
            'parent' => 'axy\creator\tests\tst\Target',
        ],
        'two' => [
            'classname' => 'axy\creator\tests\tst\Target',
            'args' => [3, 4],
            'parent' => 'axy\creator\tests\tst\Target',
        ],
        'three' => [
            'classname' => 'axy\creator\tests\tst\Target',
            'args' => [5, 6],
            'parent' => 'axy\creator\tests\tst\Target',
        ],
        'four' => [
            'classname' => 'axy\creator\tests\tst\Target',
            'args' => [7, 8],
            'parent' => 'axy\creator\tests\tst\Target',
        ],
        'five' => [
            'classname' => 'axy\creator\tests\tst\TargetChild',
            'args' => [7, 8],
            'parent' => 'axy\creator\tests\tst\TargetChild',
        ],
    ];

    private $testConfig = [
        'two' => [
            'args' => [9, 10],
            'reset_args' => true,
        ],
        'three' => [
            'axy\creator\tests\tst\TargetChild',
            [11, 12],
        ],
        'four' => false,
        'five' => 'axy\creator\tests\tst\Target',
        'x' => 1,
        'y' => 2,
    ];

    /**
     * covers ::__isset
     */
    public function testIsset()
    {
        $subs = new Subs($this->testContexts, $this->testConfig);
        $this->assertTrue(isset($subs->one));
        $this->assertTrue(isset($subs->two));
        $this->assertTrue(isset($subs['three']));
        $this->assertTrue(isset($subs['four']));
        $this->assertFalse(isset($subs->six));
        $this->assertFalse(isset($subs['x']));
    }

    /**
     * covers ::__get
     */
    public function testGetDefault()
    {
        $subs = new Subs($this->testContexts, $this->testConfig);
        $instance = $subs->one;
        $this->assertSame('axy\creator\tests\tst\Target', get_class($instance));
        $this->assertEquals([1, 2], $instance->args);
        $this->assertSame($instance, $subs->one);
        $this->assertSame($instance, $subs['one']);
    }

    /**
     * covers ::__get
     */
    public function testGetArgs()
    {
        $subs = new Subs($this->testContexts, $this->testConfig);
        $instance = $subs->two;
        $this->assertSame('axy\creator\tests\tst\Target', get_class($instance));
        $this->assertEquals([9, 10], $instance->args);
        $this->assertSame($instance, $subs->two);
        $this->assertSame($instance, $subs['two']);
    }

    /**
     * covers ::__get
     */
    public function testGetClass()
    {
        $subs = new Subs($this->testContexts, $this->testConfig);
        $instance = $subs->three;
        $this->assertSame('axy\creator\tests\tst\TargetChild', get_class($instance));
        $this->assertEquals([5, 6, 11, 12], $instance->args);
        $this->assertSame($instance, $subs->three);
        $this->assertSame($instance, $subs['three']);
        $this->assertNotSame($instance, $subs->two);
    }

    /**
     * covers ::__get
     * @expectedException \axy\creator\errors\Disabled
     */
    public function testDisable()
    {
        $subs = new Subs($this->testContexts, $this->testConfig);
        return $subs->four;
    }

    /**
     * covers ::__get
     * @expectedException \axy\creator\errors\InvalidPointer
     */
    public function testInvalidParent()
    {
        $subs = new Subs($this->testContexts, $this->testConfig);
        return $subs->five;
    }

    /**
     * covers ::__get
     */
    public function testNotFound()
    {
        $subs = new Subs($this->testContexts, $this->testConfig, 'MySubs');
        $e = null;
        try {
            $subs->__get('six');
            $this->fail('not thrown');
        } catch (ServiceNotExists $e) {
        }
        $this->assertSame('Service MySubs.six is not exists', $e->getMessage());
    }

    /**
     * covers ::__get
     */
    public function testInvalidContext()
    {
        $contexts = $this->testContexts;
        $contexts['six'] = ['six' => 6];
        $subs = new Subs($contexts, $this->testConfig);
        $this->setExpectedException('axy\creator\errors\InvalidContext');
        return $subs->six;
    }

    /**
     * covers ::__get
     */
    public function testInvalidPointer()
    {
        $config = $this->testConfig;
        $config['four'] = 4;
        $subs = new Subs($this->testContexts, $config);
        $this->setExpectedException('axy\creator\errors\InvalidPointer');
        return $subs['four'];
    }

    /**
     * covers ::__set
     * @expectedException \axy\magic\errors\ContainerReadOnly
     */
    public function testSetReadOnly()
    {
        $subs = new Subs($this->testContexts, $this->testConfig, 'MySubs');
        $subs->six = 1;
    }

    /**
     * covers ::__unset
     * @expectedException \axy\magic\errors\ContainerReadOnly
     */
    public function testUnsetReadOnly()
    {
        $subs = new Subs($this->testContexts, $this->testConfig, 'MySubs');
        unset($subs['five']);
    }
}
