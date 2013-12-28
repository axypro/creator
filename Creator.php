<?php
/**
 * @param axy\creator
 */

namespace axy\creator;

use axy\creator\helpers\ContextFormat;
use axy\creator\helpers\PointerFormat;
use axy\creator\helpers\Validator;
use axy\creator\helpers\Builder;
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
        $pointer = PointerFormat::normalize($pointer, $this->context);
        $instance = Builder::build($pointer, $this->context);
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
}
