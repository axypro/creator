<?php
/**
 * @package axy\creator
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */

namespace axy\creator\errors;

use axy\magic\errors\FieldNotExist;

/**
 * Nested service is not defined in contexts
 *
 * @link https://github.com/axypro/creator/blob/master/doc/errors.md documentation
 */
class ServiceNotExists extends FieldNotExist
{
    /**
     * {@inheritdoc}
     */
    protected $defaultMessage = 'Service {{ container }}.{{ key }} is not exists';
}
