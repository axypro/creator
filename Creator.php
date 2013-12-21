<?php
/**
 * @param axy\creator
 */

namespace axy\creator;

use axy\creator\errors\InvalidContext;
use axy\creator\errors\InvalidPointer;

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
        $diff = \array_diff_key($context, $this->context);
        if (!empty($diff)) {
            $errmsg = 'unknown index '.\implode(', ', \array_keys($diff));
            throw new InvalidContext($errmsg);
        }
        $this->context = \array_replace($this->context, $context);
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
        throw new InvalidPointer('invalid pointer type');
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
     * The creator context
     *
     * @var array
     */
    private $context = [
        'namespace' => null,
        'parent' => null,
        'validator' => null,
    ];
}
