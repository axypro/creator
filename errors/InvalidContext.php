<?php
/**
 * @package axy\creator
 */

namespace axy\creator\errors;

/**
 * Invalid format of the creator context
 *
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */
class InvalidContext extends \axy\errors\InvalidConfig
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
        parent::__construct('Creator:Context', $errmsg, $code, $previous);
    }
}
