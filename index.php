<?php
/**
 * Creation of objects by parameters
 *
 * @package axy\creator
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 * @license https://raw.github.com/axypro/creator/master/LICENSE MIT
 * @uses PHP5.4+
 * @uses axy\errors
 */

namespace axy\creator;

if (!\is_file(__DIR__.'/vendor/autoload.php')) {
    throw new \LogicException('Please: ./composer.phar install --dev');
}

require_once(__DIR__.'/vendor/autoload.php');
