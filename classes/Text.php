<?php

/**
 * DiffText - Text to compare.
 * User: demorfi@gmail.com
 */
class Text
{

    /**
     * Type the text.
     *
     * @var string
     * @access private
     */
    private $type;

    /**
     * The contents of the text.
     *
     * @var string
     * @access private
     */
    private $content;

    /**
     * Initializes a new text object.
     *
     * @param string $content Text
     * @param string $type Type of text
     * @access public
     */
    public function __construct($content, $type)
    {
        $this->content = $content;
        $this->type    = $type;
    }

    /**
     * Getting of text.
     *
     * @access public
     * @return string
     */
    public function getContent()
    {
        return ($this->content);
    }

    /**
     * Getting the type of text.
     *
     * @access public
     * @return mixed
     */
    public function getType()
    {
        return ($this->type);
    }

    /**
     * Getting of text.
     *
     * @access public
     * @return string
     */
    public function __toString()
    {
        return ($this->content);
    }

}