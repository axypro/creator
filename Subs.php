<?php
/**
 * @package axy\creator
 */

namespace axy\creator;

/**
 * Subservices that are created by a configuration
 *
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */
class Subs implements \ArrayAccess
{
    use \axy\magic\LazyField;
    use \axy\magic\ArrayMagic;
    use \axy\magic\ReadOnly;
    use \axy\magic\Named;

    /**
     * Constructor
     *
     * @param array $contexts
     *        the list of subservices contexts (key => context)
     * @param array $config
     *        the system configuration
     * @param string $name [optional]
     *        the optional name of service (for debug)
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
        throw new errors\ServiceNotExists($key, $this, null, __NAMESPACE__);
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
