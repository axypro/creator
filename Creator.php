<?php
/**
 * @param axy\creator
 */

namespace axy\creator;

use axy\creator\errors\InvalidContext;
use axy\creator\errors\InvalidPointer;

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
        $diff = \array_diff_key($context, $this->context);
        if (!empty($diff)) {
            $errmsg = 'unknown index '.\implode(', ', \array_keys($diff));
            throw new InvalidContext($errmsg);
        }
        $this->context = \array_replace($this->context, $context);
        if (!empty($this->context['namespace'])) {
            $ns = $this->context['namespace'];
            $ns = \preg_replace('/^\\\\/s', '', $ns);
            $ns = \preg_replace('/\\\\$/s', '', $ns);
            $this->context['namespace'] = $ns.'\\';
        }
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
        $this->validation($instance);
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
        if (\is_object($pointer)) {
            return $pointer;
        }
        if (\is_string($pointer)) {
            return $this->createByClass($pointer, []);
        }
        if (!\is_array($pointer)) {
            throw new InvalidPointer('invalid pointer type');
        }
        if (\array_key_exists('value', $pointer)) {
            return $pointer['value'];
        }
        throw new InvalidPointer('invalid pointer format');
    }

    /**
     * Validate the result
     *
     * @param object $instance
     * @thorws \axy\creator\errors\InvalidPointer
     */
    protected function validation($instance)
    {
        if (!empty($this->context['parent'])) {
            if (!($instance instanceof $this->context['parent'])) {
                $errmsg = 'The result should be the subclass of '.$this->context['parent'].'. ';
                $errmsg .= 'It is instance of '.\get_class($instance);
                throw new InvalidPointer($errmsg);
            }
        }
        if (!empty($this->context['validator'])) {
            if (!\call_user_func($this->context['validator'], $instance)) {
                $errmsg = 'The result validation failed';
                throw new InvalidPointer($errmsg);
            }
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
        $class = new \ReflectionClass($classname);
        return $class->newInstanceArgs($args);
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
        'classname' => null,
        'creator' => null,
        'use_options' => false,
        'args' => null,
        'append_args' => null,
    ];
}
