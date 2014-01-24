<?php
/**
 * @param axy\creator
 */

namespace axy\creator;

/**
 * Lazy creation of an object
 *
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */
class Lazy
{
    /**
     * Constructor
     *
     * @param \axy\creator\Creator|array $creator
     *        a creator or a creator context
     * @param mixed $pointer
     *        a pointer to an object
     */
    public function __construct($creator, $pointer)
    {
        $this->creator = $creator;
        $this->pointer = $pointer;
    }

    /**
     * Creates and returns the object
     *
     * @return mixed
     * @throws \axy\creator\errors\InvalidPointer
     * @throws \axy\creator\errors\Disabled
     * @throws \axy\creator\errors\InvalidContext
     */
    public function __invoke()
    {
        if (!$this->created) {
            if (!($this->creator instanceof Creator)) {
                $this->creator = new Creator($this->creator);
            }
            $this->target = $this->creator->create($this->pointer);
            $this->creator = null;
            $this->pointer = null;
            $this->created = true;
        }
        return $this->target;
    }

    /**
     * @var \axy\creator\Creator
     */
    private $creator;

    /**
     * @var mixed
     */
    private $pointer;

    /**
     * @var mixed
     */
    private $target;

    /**
     * @var boolean
     */
    private $created = false;
}
