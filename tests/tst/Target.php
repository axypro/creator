<?php
/**
 * @package axy\creator
 */

namespace axy\creator\tests\tst;

class Target
{
    public $args;

    public static $count = 0;

    public function __construct()
    {
        $this->args = func_get_args();
        self::$count++;
    }
}
