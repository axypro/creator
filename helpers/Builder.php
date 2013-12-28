<?php
/**
 * @package axy\creator
 */

namespace axy\creator\helpers;

use axy\creator\errors\InvalidPointer;
use axy\creator\helpers\Args;
use axy\callbacks\Callback;

/**
 * Build objects by the pointer
 *
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */
class Builder
{
    /**
     * Builds an object by the pointer
     *
     * @param array $pointer
     *        the normalized pointer
     * @param array $context
     *        the normalized context
     * @return mixed
     *         the target object
     * @throws \axy\creator\errors\InvalidPointer
     */
    public static function build(array $pointer, array $context)
    {
        if (\array_key_exists('value', $pointer)) {
            return $pointer['value'];
        }
        if (!empty($pointer['classname'])) {
            $classname = $pointer['classname'];
            if ((!empty($context['namespace'])) && ($classname[0] !== '\\')) {
                $classname = $context['namespace'].$classname;
            }
            return self::buildByClassname($classname, $pointer, $context);
        }
        if (!empty($pointer['creator'])) {
            return Callback::call($pointer['creator'], [$pointer, $context]);
        }
        if (!empty($context['classname'])) {
            return self::buildByClassname($context['classname'], $pointer, $context);
        }
        if (!empty($context['creator'])) {
            return Callback::call($context['creator'], [$pointer, $context]);
        }
        throw new InvalidPointer('');
    }

    /**
     * @param string $classname
     * @param array $pointer
     * @param array $context
     * @return mixed
     * @throws \axy\creator\errors\InvalidPointer
     */
    private static function buildByClassname($classname, $pointer, $context)
    {
        if (!\class_exists($classname, true)) {
            throw new InvalidPointer('class "'.$classname.'" not found');
        }
        $args = Args::createArgs($pointer, $context);
        if (empty($args)) {
            $instance = new $classname();
        } else {
            $class = new \ReflectionClass($classname);
            $instance = $class->newInstanceArgs($args);
        }
        return $instance;
    }
}
