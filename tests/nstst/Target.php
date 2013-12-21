<?php
/**
 * @package axy\creator
 */

namespace axy\creator\tests\nstst;

class Target
{
    public $args;

    public function __construct()
    {
        $this->args = \func_get_args();
    }
}
