<?php

/**
 * DiffText - Object status.
 * User: demorfi@gmail.com
 */
class Stat
{

    /**
     * Status.
     *
     * @var string
     * @access private
     */
    private $type;

    /**
     * Set removed status.
     *
     * @access public
     * @return Stat
     */
    public function setRemove()
    {
        $this->type = 'remove';
        return ($this);
    }

    /**
     * Set added status.
     *
     * @access public
     * @return Stat
     */
    public function setAdd()
    {
        $this->type = 'add';
        return ($this);
    }

    /**
     * Set changed status.
     *
     * @access public
     * @return Stat
     */
    public function setChange()
    {
        $this->type = 'change';
        return ($this);
    }

    /**
     * Set equivalent status.
     *
     * @access public
     * @return Stat
     */
    public function setEqual()
    {
        $this->type = 'equal';
        return ($this);
    }

    /**
     * Has status of removed.
     *
     * @access public
     * @return bool
     */
    public function hasRemove()
    {
        return ($this->type === 'remove');
    }

    /**
     * Has status of added.
     *
     * @access public
     * @return bool
     */
    public function hasAdd()
    {
        return ($this->type === 'add');
    }

    /**
     * Has status of changed.
     *
     * @access public
     * @return bool
     */
    public function hasChange()
    {
        return ($this->type === 'change');
    }

    /**
     * Has status of equivalent.
     *
     * @access public
     * @return bool
     */
    public function hasEqual()
    {
        return ($this->type === 'equal');
    }

    /**
     * Getting of status.
     *
     * @access public
     * @return string
     */
    public function __toString()
    {
        return ($this->type);
    }
}