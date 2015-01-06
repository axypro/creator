<?php
/**
 * @param axy\creator
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */

namespace axy\creator;

use axy\creator\helpers\ContextFormat;
use axy\creator\helpers\PointerFormat;
use axy\creator\helpers\Validator;
use axy\creator\helpers\Builder;

/**
 * The class for building of objects by the specified parameters
 */
class Creator
{
    /**
     * The constructor
     *
     * @param array $context [optional]
     *        the creator context
     * @throws \axy\creator\errors\InvalidContext
     *         the context has invalid format
     */
    final public function __construct(array $context = array())
    {
        $this->context = ContextFormat::normalize($context);
    }

    /**
     * Creates an object by the pointer
     *
     * @param mixed $pointer
     * @return object
     * @throws \axy\creator\errors\InvalidPointer
     */
    public function create($pointer)
    {
        $pointer = PointerFormat::normalize($pointer, $this->context);
        $instance = Builder::build($pointer, $this->context);
        Validator::validate($instance, $this->context);
        return $instance;
    }

    /**
     * Invoke: creates an object by the pointer
     *
     * @param mixed $pointer
     * @return object
     * @throws \axy\creator\errors\InvalidPointer
     */
    final public function __invoke($pointer)
    {
        return $this->create($pointer);
    }

    /**
     * Creates a block of objects
     *
     * @param array $block
     * @return array
     * @throws \axy\creator\errors\InvalidPointer
     */
    final public function blockCreate(array $block)
    {
        $result = [];
        foreach ($block as $k => $pointer) {
            $result[$k] = $this->create($pointer);
        }
        return $result;
    }

    /**
     * Returns the normalized context of the creator
     *
     * @return array
     */
    final public function getContext()
    {
        return $this->context;
    }

    /**
     * Creates a lazy object
     *
     * @param mixed $pointer
     * @return \axy\creator\Lazy
     */
    public function lazy($pointer)
    {
        return new Lazy($this, $pointer);
    }
}
