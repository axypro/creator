<?php
/**
 * @package axy\creator
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */

namespace axy\creator;

use axy\magic\LazyField;
use axy\magic\ArrayMagic;
use axy\magic\ReadOnly;
use axy\magic\Named;
use axy\creator\errors\ServiceNotExists;

/**
 * Nested services that are created by a configuration
 *
 * @link https://github.com/axypro/creator/blob/master/doc/Subs.md documentation
 */
class Subs implements \ArrayAccess
{
    use LazyField;
    use ArrayMagic;
    use ReadOnly;
    use Named;

    /**
     * The constructor
     *
     * @param array $contexts
     *        the list of nested services contexts (key => context)
     * @param array $config
     *        the system configuration
     * @param string $name [optional]
     *        the optional name of the aggregator (for debug)
     */
    public function __construct(array $contexts, array $config, $name = 'Subs')
    {
        $this->contexts = $contexts;
        $this->config = $config;
        $this->magicName = $name;
    }

    /**
     * {@inheritdoc}
     */
    protected function magicExistsField($key)
    {
        return isset($this->contexts[$key]);
    }

    /**
     * {@inheritdoc}
     */
    protected function magicCreateField($key)
    {
        if (!isset($this->contexts[$key])) {
            return $this->magicErrorFieldNotFound($key);
        }
        $pointer = isset($this->config[$key]) ? $this->config[$key] : null;
        $creator = new Creator($this->contexts[$key]);
        return $creator->create($pointer);
    }

    /**
     * {@inheritdoc}
     */
    protected function magicErrorFieldNotFound($key)
    {
        throw new ServiceNotExists($key, $this, null, __NAMESPACE__);
    }

    /**
     * @var array
     */
    private $contexts;

    /**
     * @var array
     */
    private $config;

    /**
     * @var string
     */
    private $magicName;
}
