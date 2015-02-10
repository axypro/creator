<?php
/**
 * @package axy\creator
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */

namespace axy\creator\errors;

use axy\errors\InvalidConfig;

/**
 * Invalid format of the creator context
 *
 * @link https://github.com/axypro/creator/blob/master/doc/errors.md documentation
 */
class InvalidContext extends InvalidConfig
{
    /**
     * The constructor
     *
     * @param string $errorMessage [optional]
     * @param int $code [optional]
     * @param \Exception $previous [optional]
     * @param mixed $thrower [optional]
     */
    public function __construct($errorMessage = null, $code = 0, \Exception $previous = null, $thrower = null)
    {
        parent::__construct('Creator:Context', $errorMessage, $code, $previous, $thrower);
    }
}
