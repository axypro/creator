<?php
/**
 * @package axy\creator
 */

namespace axy\creator\errors;

/**
 * Invalid format of an object pointer
 *
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */
class InvalidPointer extends \axy\errors\InvalidConfig
{
    /**
     * Constructor
     *
     * @param string $errmsg [optional]
     * @param int $code [optional]
     * @param \Exception $previous [optional]
     */
    public function __construct($errmsg = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct('Creator:Pointer', $errmsg, $code, $previous);
    }
}
