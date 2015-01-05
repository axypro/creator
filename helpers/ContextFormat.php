<?php
/**
 * @package axy\creator
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */

namespace axy\creator\helpers;

use axy\creator\errors\InvalidContext;

/**
 * Normalization of context format
 */
class ContextFormat
{
    /**
     * Normalizes the context format
     *
     * @param array $context
     * @return array
     * @throws \axy\creator\errors\InvalidContext
     */
    public static function normalize(array $context)
    {
        $diff = array_diff_key($context, self::$defaults);
        if (!empty($diff)) {
            $errorMessage = 'unknown index '.implode(', ', array_keys($diff));
            throw new InvalidContext($errorMessage);
        }
        if (!empty($context['namespace'])) {
            if (preg_match('/^\\\\?(.*?)\\\\?$/s', $context['namespace'], $ns)) {
                $context['namespace'] = $ns[1] ? $ns[1].'\\' : null;
            }
        } else {
            $context['namespace'] = null;
        }
        return array_replace(self::$defaults, $context);
    }

    /**
     * @var array
     */
    private static $defaults = [
        'namespace' => '',
        'args' => [],
        'append_args' => [],
        'use_options' => false,
        'classname' => null,
        'creator' => null,
        'parent' => null,
        'validator' => null,
    ];
}
