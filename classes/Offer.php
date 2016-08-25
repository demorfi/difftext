<?php

/**
 * DiffText - Offer.
 * User: demorfi@gmail.com
 */
class Offer
{

    /**
     * The text of offer.
     *
     * @var string
     * @access private
     */
    private $text;

    /**
     * The text of old offer.
     *
     * @var string
     * @access private
     */
    private $old;

    /**
     * The text of new offer.
     *
     * @var string
     * @access private
     */
    private $new;

    /**
     * The status of offer.
     *
     * @var Stat
     * @access private
     */
    private $stat;

    /**
     * Initialize of the new offer.
     *
     * @param string [$text] The text of offer
     * @access public
     */
    public function __construct($text = '')
    {
        $this->text = $text;
    }

    /**
     * Getting text offer.
     *
     * @access public
     * @return string
     */
    public function getText()
    {
        return ($this->text);
    }

    /**
     * Setting old offer.
     *
     * @param string $text The text of old offer
     * @access public
     * @return Offer
     */
    public function setOld($text)
    {
        $this->old = $text;
        return ($this);
    }

    /**
     * Getting old offer.
     *
     * @access public
     * @return string
     */
    public function getOld()
    {
        return ($this->old);
    }

    /**
     * Setting new offer.
     *
     * @param string $text The text of new offer
     * @access public
     * @return Offer
     */
    public function setNew($text)
    {
        $this->new = $text;
        return ($this);
    }

    /**
     * Getting new offer.
     *
     * @access public
     * @return mixed
     */
    public function getNew()
    {
        return ($this->new);
    }

    /**
     * Setting status of offer.
     *
     * @param Stat $stat Status offer
     * @access public
     * @return Offer
     */
    public function setStat(Stat $stat)
    {
        $this->stat = $stat;
        return ($this);
    }

    /**
     * Getting status of offer.
     *
     * @access public
     * @return Stat
     */
    public function getStat()
    {
        return ($this->stat);
    }

    /**
     * Getting text offer.
     *
     * @access public
     * @return string
     */
    public function __getString()
    {
        return ($this->text);
    }
}