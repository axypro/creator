<?php
/**
 * @param axy\creator
 */

namespace axy\creator;

use axy\creator\helpers\ContextFormat;
use axy\creator\helpers\PointerFormat;
use axy\creator\helpers\Validator;
use axy\creator\helpers\Args;
use axy\creator\errors\InvalidPointer;
use axy\callbacks\Callback;

/**
 * The class for building of objects by the specified parameters
 *
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */
class Creator
{
    /**
     * Constructor
     *
     * @param array $context [optional]
     * @throws \axy\creator\errors\InvalidContext
     */
    final public function __construct(array $context = array())
    {
        $this->context = ContextFormat::normalize($context);
    }

    /**
     * Create an object by the pointer
     *
     * @param mixed $pointer
     * @return object
     * @thorws \axy\creator\errors\InvalidPointer
     */
    public function create($pointer)
    {
        $instance = $this->createByPointer($pointer);
        Validator::validate($instance, $this->context);
        return $instance;
    }

    /**
     * Invoke: create an object by the pointer
     *
     * @param mixed $pointer
     * @return object
     * @thorws \axy\creator\errors\InvalidPointer
     */
    final public function __invoke($pointer)
    {
        return $this->create($pointer);
    }

    /**
     * Get the normalized context of creator
     *
     * @return array
     */
    final public function getContext()
    {
        return $this->context;
    }

    /**
     * @param mixed $pointer
     * @return object
     * @thorws \axy\creator\errors\InvalidPointer
     */
    protected function createByPointer($pointer)
    {
        $pointer = PointerFormat::normalize($pointer, $this->context);
        if (\array_key_exists('value', $pointer)) {
            return $pointer['value'];
        }
        if (!empty($pointer['creator'])) {
            return Callback::call($pointer['creator'], [$pointer, $this->context]);
        }
        if (!empty($pointer['classname'])) {
            $args = Args::createArgs($pointer, $this->context);
            return $this->createByClass($pointer['classname'], $args);
        }
    }

    /**
     * Create an instance of a class
     *
     * @param string $classname
     * @param array $args
     * @return object
     * @thorws \axy\creator\errors\InvalidPointer
     */
    protected function createByClass($classname, $args)
    {
        if (empty($classname)) {
            throw new InvalidPointer('class name is empty');
        }
        if (!empty($this->context['namespace'])) {
            if ($classname[0] !== '\\') {
                $classname = $this->context['namespace'].$classname;
            }
        }
        if (!\class_exists($classname, true)) {
            throw new InvalidPointer('class "'.$classname.'" is not exists');
        }
        if (empty($args)) {
            return new $classname();
        }
        $class = new \ReflectionClass($classname);
        return $class->newInstanceArgs($args);
    }
}
