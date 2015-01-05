<?php
/**
 * @package axy\creator
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */

namespace axy\creator\helpers;

use axy\creator\errors\InvalidPointer;
use axy\callbacks\Callback;

/**
 * Validate a result of creating
 */
class Validator
{
    /**
     * Checks a result
     *
     * @param mixed $result
     *        the result of creating
     * @param array $context
     *        the context of the creator
     * @return boolean
     * @throws \axy\creator\errors\InvalidPointer
     */
    public static function validate($result, array $context)
    {
        if (!empty($context['parent'])) {
            if (!($result instanceof $context['parent'])) {
                throw new InvalidPointer('Object must be an instance of '.$context['parent']);
            }
        }
        if (!empty($context['validator'])) {
            if (!Callback::call($context['validator'], [$result])) {
                throw new InvalidPointer('Object is not valid');
            }
        }
        return true;
    }
}
