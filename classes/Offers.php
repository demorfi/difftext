<?php

/**
 * DiffText - The collection offers.
 * User: demorfi@gmail.com
 */
class Offers extends ArrayIterator
{

    /**
     * Initialize of the new offers collection.
     * Each element of the array will offer object.
     *
     * @inheritdoc
     */
    public function __construct($array = [], $flags = 0)
    {
        parent::__construct([], $flags);
        foreach ($array as $value) {
            parent::append(new Offer($value));
        }
    }

    /**
     * Adding a collection.
     *
     * @param Offer $offer Offer
     * @throws InvalidArgumentException If this is not the type Offer
     * @access public
     * @return void
     */
    public function append($offer)
    {
        if (!($offer instanceof Offer)) {
            throw new InvalidArgumentException('It requires the addition of type Offer');
        }
        parent::append($offer);
    }

    /**
     *  Search of offer in text.
     *
     * @param Offer $offer Offer object
     * @access public
     * @return bool
     */
    public function existsValue(Offer $offer)
    {
        $index = $this->key();

        $this->rewind();
        while ($this->valid()) {
            if ($this->current()->getText() === $offer->getText()) {
                $this->seek($index);
                return (true);
            }
            $this->next();
        }

        $this->seek($index);
        return (false);
    }
}