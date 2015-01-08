<?php
/**
 * @package axy\creator
 */

namespace axy\creator\tests\helpers;

use axy\creator\helpers\NameResolver;
use axy\creator\helpers\ContextFormat;

/**
 * coversDefaultClass axy\creator\helpers\NameResolver
 */
class NameResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * covers ::resolve
     */
    public function testResolveRoot()
    {
        $context = [];
        $context = ContextFormat::normalize($context);
        $this->assertSame('one\Two', NameResolver::resolve('\one\Two', $context));
        $this->assertSame('one\Two', NameResolver::resolve('one\Two', $context));
        $this->setExpectedException('axy\creator\errors\InvalidPointer');
        NameResolver::resolve('package.module:Two', $context);
    }

    /**
     * covers ::resolve
     */
    public function testResolveShort()
    {
        $context = [
            'namespace' => 'my\ns',
        ];
        $context = ContextFormat::normalize($context);
        $this->assertSame('one\Two', NameResolver::resolve('\one\Two', $context));
        $this->assertSame('my\ns\one\Two', NameResolver::resolve('one\Two', $context));
        $this->setExpectedException('axy\creator\errors\InvalidPointer');
        NameResolver::resolve('package.module:Two', $context);
    }

    /**
     * covers ::resolve
     */
    public function testResolveModules()
    {
        $context = [
            'namespace' => 'my\ns',
            'modules' => [
                'package.module' => 'pkg\mo',
                'other' => 'other\mo',
            ],
        ];
        $context = ContextFormat::normalize($context);
        $this->assertSame('one\Two', NameResolver::resolve('\one\Two', $context));
        $this->assertSame('my\ns\one\Two', NameResolver::resolve('one\Two', $context));
        $this->assertSame('pkg\mo\one\Two', NameResolver::resolve('package.module:one\Two', $context));
        $this->setExpectedException('axy\creator\errors\InvalidPointer');
        NameResolver::resolve('qwerty:one\Two', $context);
    }

    /**
     * covers ::resolve
     */
    public function testResolveModuleResolver()
    {
        $context = [
            'namespace' => 'my\ns',
            'modules' => [
                'package.module' => 'pkg\mo',
                'other' => 'other\mo',
            ],
            'moduleResolver' => [
                'function' => function ($module, $shortName) {
                    if ($module === 'qwerty') {
                        return 'qwe\rty\\'.$shortName;
                    }
                    return null;
                }
            ],
        ];
        $context = ContextFormat::normalize($context);
        $this->assertSame('one\Two', NameResolver::resolve('\one\Two', $context));
        $this->assertSame('my\ns\one\Two', NameResolver::resolve('one\Two', $context));
        $this->assertSame('pkg\mo\one\Two', NameResolver::resolve('package.module:one\Two', $context));
        $this->assertSame('qwe\rty\one\Two', NameResolver::resolve('qwerty:one\Two', $context));
        $this->setExpectedException('axy\creator\errors\InvalidPointer');
        NameResolver::resolve('uiop:one\Two', $context);
    }
}
