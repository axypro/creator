<?php
/**
 * @package axy\creator
 */

namespace axy\creator\helpers;

use axy\creator\errors\InvalidPointer;

/**
 * Creating a list of arguments for the constructor
 *
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */
class Args
{
    /**
     * Creates a arguments list
     *
     * @param array $pointer
     *        the normalized pointer
     * @param array $context
     *        the normalized context
     * @return array
     *         the arguments list
     * @throws \axy\creator\errors\InvalidPointer
     */
    public static function createArgs(array $pointer, array $context)
    {
        if (\array_key_exists('args', $pointer)) {
            $args = $pointer['args'];
            if (!\is_array($args)) {
                throw new InvalidPointer('args must be an array');
            }
        } elseif (\array_key_exists('options', $pointer)) {
            $args = [$pointer['options']];
        } else {
            $args = [];
        }
        if (!empty($pointer['reset_args'])) {
            return $args;
        }
        if (!empty($context['args'])) {
            $args = \array_merge($context['args'], $args);
        }
        if (!empty($context['append_args'])) {
            $args = \array_merge($args, $context['append_args']);
        }
        return $args;
    }
}
