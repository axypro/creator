<?php
/**
 * @package axy\creator
 */

namespace axy\creator\helpers;

use axy\creator\errors\InvalidPointer;
use axy\creator\errors\Disabled;

/**
 * Normalization of pointer format
 *
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */
class PointerFormat
{
    /**
     * Normalizes of pointer format
     *
     * @param mixed $pointer
     * @param array $context [optional]
     * @return array
     * @throws \axy\creator\errors\InvalidPointer
     * @throws \axy\creator\errors\Disabled
     */
    public static function normalize($pointer, array $context = null)
    {
        if (\is_object($pointer)) {
            return ['value' => $pointer];
        }
        if (\is_string($pointer)) {
            return ['classname' => $pointer];
        }
        if ($pointer === null) {
            return [];
        }
        if ($pointer === false) {
            throw new Disabled('');
        }
        if (!\is_array($pointer)) {
            throw new InvalidPointer('Invalid of pointer type');
        }
        if (!isset($pointer[0])) {
            return $pointer;
        }
        $result = [
            'classname' => $pointer[0],
        ];
        if (!isset($pointer[1])) {
            return $result;
        }
        $args = $pointer[1];
        if (empty($context['use_options'])) {
            if (!\is_array($args)) {
                throw new InvalidPointer('Args must be an array');
            }
            $result['args'] = $args;
        } else {
            $result['options'] = $args;
        }
        return $result;
    }
}
