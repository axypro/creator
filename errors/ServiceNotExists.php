<?php
/**
 * @package axy\creator
 */

namespace axy\creator\errors;

/**
 * Subservice is not defined in contexts
 *
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */
class ServiceNotExists extends \axy\magic\errors\FieldNotExist
{
    /**
     * {@inheritdoc}
     */
    protected $defaultMessage = 'Service {{ container }}.{{ key }} is not exists';
}
