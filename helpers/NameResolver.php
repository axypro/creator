<?php
/**
 * @package axy\creator
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */

namespace axy\creator\helpers;

use axy\callbacks\Callback;
use axy\creator\errors\InvalidPointer;

/**
 * The helper for resolve a classname to the full class name
 */
class NameResolver
{
    /**
     * Resolves a class name
     *
     * @param string $classname
     * @param array $context
     * @return string
     * @throws \axy\creator\errors\InvalidPointer
     */
    public static function resolve($classname, array $context)
    {
        if ($classname[0] === '\\') {
            return substr($classname, 1);
        }
        $cn = explode(':', $classname, 2);
        if (count($cn) === 1) {
            if (!empty($context['namespace'])) {
                $classname = $context['namespace'].$classname;
            }
            return $classname;
        }
        $module = $cn[0];
        $cn = $cn[1];
        if (!empty($context['modules'])) {
            $modules = $context['modules'];
            if (isset($modules[$module])) {
                return $modules[$module].'\\'.$cn;
            }
        }
        if (!empty($context['moduleResolver'])) {
            $cn = Callback::call($context['moduleResolver'], [$module, $cn]);
            if (is_string($cn)) {
                return $cn;
            }
        }
        throw new InvalidPointer($classname);
    }
}
