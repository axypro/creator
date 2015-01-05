<?php
/**
 * @package axy\creator
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */

namespace axy\creator\errors;

use axy\errors\InvalidConfig;

/**
 * Invalid format of an object pointer
 */
class InvalidPointer extends InvalidConfig
{
    /**
     * Constructor
     *
     * @param string $errorMessage [optional]
     * @param int $code [optional]
     * @param \Exception $previous [optional]
     * @param mixed $thrower [optional]
     */
    public function __construct($errorMessage = null, $code = 0, \Exception $previous = null, $thrower = null)
    {
        parent::__construct('Creator:Pointer', $errorMessage, $code, $previous, $thrower);
    }
}
