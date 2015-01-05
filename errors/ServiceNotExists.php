<?php
/**
 * @package axy\creator
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */

namespace axy\creator\errors;

use axy\magic\errors\FieldNotExist;

/**
 * Subservice is not defined in contexts
 */
class ServiceNotExists extends FieldNotExist
{
    /**
     * {@inheritdoc}
     */
    protected $defaultMessage = 'Service {{ container }}.{{ key }} is not exists';
}
