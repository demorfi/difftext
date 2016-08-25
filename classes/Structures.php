<?php

/**
 * DiffText - Collection structures.
 * User: demorfi@gmail.com
 */
class Structures extends ArrayIterator
{

    /**
     * The types of structures.
     *
     * @var array
     * @access private
     */
    private $types = [];

    /**
     * Active type of structure.
     *
     * @var string
     * @access private
     */
    private $currentType;

    /**
     * Adding the type of structure.
     *
     * @param Text $text Text object
     * @access public
     * @return void
     */
    public function addType(Text $text)
    {
        if (!in_array($text->getType(), $this->types)) {
            $this->types[] = $text->getType();
        }
    }

    /**
     * Changing the structure of the active type.
     *
     * @param Text $text Text object
     * @access public
     * @return void
     */
    public function switchToType(Text $text)
    {
        $this->currentType = $text->getType();
        $this->rewind();
    }

    /**
     * Getting the current new structure.
     *
     * @access public
     * @return Structure
     */
    public function getNew()
    {
        $current = $this->current();
        return (isset($current['new']) ? $current['new'] : new Structure('', new Offers()));
    }

    /**
     * Getting the current old structure.
     *
     * @access public
     * @return Structure
     */
    public function getOld()
    {
        $current = $this->current();
        return (isset($current['old']) ? $current['old'] : new Structure('', new Offers()));
    }

    /**
     * Has of the current old in structure.
     *
     * @access public
     * @return bool
     */
    public function hasOld()
    {
        $current = $this->current();
        return (isset($current['old']));
    }

    /**
     * Has of the current new in structure.
     *
     * @access public
     * @return bool
     */
    public function hasNew()
    {
        $current = $this->current();
        return (isset($current['new']));
    }

    /**
     * Has of the current new and old in structure.
     *
     * @access public
     * @return bool
     */
    public function hasOldAndNew()
    {
        $current = $this->current();
        return (isset($current['old'], $current['new']));
    }

    /**
     * Adding structure to the collection.
     *
     * @param Structure $structure The structure of offers
     * @throws InvalidArgumentException If this is not the type Structure
     * @access public
     * @return void
     */
    public function append($structure)
    {
        if (!($structure instanceof Structure)) {
            throw new InvalidArgumentException('It requires the addition of type Structure');
        }

        $current = $this->current();

        // merging the collection structure with another type
        if ($this->key() !== null && !isset($current[$this->currentType])) {
            $this->offsetSet(
                $this->key(),
                array_merge($this->offsetGet($this->key()), [$this->currentType => $structure])
            );
            $this->next();
        } else {
            parent::append([$this->currentType => $structure]);
        }
    }

    /**
     * Setting status of the current structure.
     *
     * @param Stat $stat
     * @access public
     * @return void
     */
    public function setStat(Stat $stat)
    {
        if ($this->key() !== null) {
            $this->offsetSet($this->key(), array_merge($this->offsetGet($this->key()), ['stat' => $stat]));
        }
    }

    /**
     * Getting status of the current structure.
     *
     * @access public
     * @return Stat
     */
    public function getStat()
    {
        $current = $this->current();
        return (isset($current['stat']) ? $current['stat'] : new Stat());
    }

    /**
     * Setting list of offers current structure.
     *
     * @param Offers $offers The collection offers
     * @access public
     * @return void
     */
    public function setOffers(Offers $offers)
    {
        if ($this->key() !== null) {
            $this->offsetSet($this->key(), array_merge($this->offsetGet($this->key()), ['offers' => $offers]));
        }
    }

    /**
     * Getting list of offers current structure.
     *
     * @access public
     * @return Offers
     */
    public function getOffers()
    {
        $current = $this->current();
        return (isset($current['offers']) ? $current['offers'] : new Offers());
    }
}