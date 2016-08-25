<?php

/**
 * DiffText - The structure of offers.
 * User: demorfi@gmail.com
 */
class Structure
{

    /**
     * The text of offers.
     *
     * @var string
     * @access private
     */
    private $text;

    /**
     * The collection offers.
     *
     * @var Offers
     * @access private
     */
    private $offers;

    /**
     * Initialize of the new structure.
     *
     * @param string $text The text of offers
     * @param Offers $offers The collection offers
     * @access public
     */
    public function __construct($text, Offers $offers)
    {
        $this->text   = $text;
        $this->offers = $offers;
    }

    /**
     * Getting text of offers.
     *
     * @access public
     * @return string
     */
    public function getText()
    {
        return ($this->text);
    }

    /**
     * Getting the collection of offers.
     *
     * @access public
     * @return Offers
     */
    public function getOffers()
    {
        return ($this->offers);
    }

    /**
     * Getting the size of the collection of offers.
     *
     * @access public
     * @return int
     */
    public function size()
    {
        return (sizeof($this->offers));
    }

    /**
     * Is of an empty text of offers.
     *
     * @access public
     * @return bool
     */
    public function isEmpty()
    {
        return ($this->text === '');
    }

    /**
     * Getting text of offers.
     *
     * @access public
     * @return string
     */
    public function __toString()
    {
        return ($this->text);
    }
}