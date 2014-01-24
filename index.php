<?php
/**
 * Creation of objects by parameters
 *
 * @package axy\creator
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 * @license https://raw.github.com/axypro/creator/master/LICENSE MIT
 * @link https://github.com/axypro/creator repository
 * @link https://github.com/axypro/creator/wiki documentation
 * @link https://packagist.org/packages/axy/creator on packagist.org
 * @uses PHP5.4+
 */

namespace axy\creator;

if (!\is_file(__DIR__.'/vendor/autoload.php')) {
    throw new \LogicException('Please: ./composer.phar install --dev');
}

require_once(__DIR__.'/vendor/autoload.php');
