<?php

/**
 * DiffText.
 * User: demorfi@gmail.com
 */
class DiffText
{

    /**
     * Primary text.
     *
     * @var string
     * @access private
     */
    private $primary = '';

    /**
     * Second text.
     *
     * @var string
     * @access private
     */
    private $second = '';

    /**
     * Number of differences in text.
     *
     * @var int
     * @access private
     */
    private $numberOfDifferences = 0;

    /**
     * Excluded offers in the period of processing.
     *
     * @var array
     * @access private
     */
    private $excluded = [];

    /**
     * Set text to compare.
     *
     * @param string $primary Primary text
     * @param string $second Second text
     * @access public
     */
    public function __construct($primary, $second)
    {
        $this->primary = new Text($primary, 'old');
        $this->second  = new Text($second, 'new');
    }

    /**
     * Get number of differences in text.
     *
     * @access public
     * @return int
     */
    public function getNumberOfDifferences()
    {
        return ($this->numberOfDifferences);
    }

    /**
     * Comparison of the texts.
     *
     * @access public
     * @return Structures
     */
    public function diff()
    {
        $structures = new Structures();
        $structures->addType($this->primary);
        $structures->addType($this->second);

        $parse = new ParseText($structures);
        $parse->doParse($this->primary);
        $parse->doParse($this->second);

        while ($structures->valid()) {

            if ($structures->hasOldAndNew()) {

                if ($structures->getOld()->getText() === $structures->getNew()->getText()) {
                    $structures->setStat((new Stat())->setEqual());
                } else {
                    $structures->setStat((new Stat())->setChange());

                    if ($structures->getNew()->isEmpty()) {
                        $structures->setStat((new Stat())->setRemove());
                    }

                    if ($structures->getOld()->isEmpty()) {
                        $structures->setStat((new Stat())->setAdd());
                    }
                }

                $sizeOld = $structures->getOld()->size();
                $sizeNew = $structures->getNew()->size();

                // many offers in line
                if ($sizeOld > 1 || $sizeNew > 1) {
                    $offers = new Offers();

                    $newOffers = $structures->getNew()->getOffers();
                    $oldOffers = $structures->getOld()->getOffers();
                    while ($this->nextValidOffer($offers, $newOffers, $oldOffers)) {

                        $newOffer = $newOffers->current();
                        if ($oldOffers->offsetExists($newOffers->key())) {
                            $oldOffer = $oldOffers->offsetGet($newOffers->key());

                            $percent = 0;
                            similar_text($newOffer->getText(), $oldOffer->getText(), $percent);

                            // change or equal offer
                            if ($percent > 50) {
                                $offer = new Offer();
                                $offer->setNew($newOffer->getText())
                                    ->setOld($oldOffer->getText())
                                    ->setStat($percent >= 100 ? (new Stat())->setEqual() : (new Stat())->setChange());

                                $offers->append($offer);
                            } else {

                                // add offer
                                $offer = new Offer();
                                $offer->setNew($newOffer->getText())
                                    ->setStat((new Stat())->setAdd());
                                $offers->append($offer);

                                // remove offer
                                if (!$newOffers->existsValue($oldOffer)) {
                                    $offer = new Offer();
                                    $offer->setOld($oldOffer->getText())
                                        ->setStat((new Stat())->setRemove());
                                    $offers->append($offer);
                                }
                            }

                            $this->numberOfDifferences++;
                        } else {

                            // add offer
                            $offer = new Offer();
                            $offer->setNew($newOffer->getText())
                                ->setStat((new Stat())->setAdd());

                            $offers->append($offer);
                            $this->numberOfDifferences++;
                        }

                        $newOffers->next();
                    }

                    if ($sizeOld > $sizeNew) {
                        for ($i = $sizeNew; $i < $sizeOld; $i++) {
                            $offer = new Offer();
                            $offer->setOld($structures->getOld()->getOffers()->offsetGet($i)->getText())
                                ->setStat((new Stat())->setRemove());

                            $offers->append($offer);
                            $this->numberOfDifferences++;
                        }
                    }

                    $structures->setOffers($offers);
                } else {

                    if (!$structures->getStat()->hasEqual()) {
                        $this->numberOfDifferences++;
                    }
                }

            } else {

                // Add status to deleted row
                if ($structures->hasOld() && (!$structures->hasNew() || $structures->getNew()->isEmpty())) {
                    $structures->setStat((new Stat())->setRemove());
                    $this->numberOfDifferences++;
                }

                // Add status to added row
                if ($structures->hasNew() && (!$structures->hasOld() || $structures->getOld()->isEmpty())) {
                    $structures->setStat((new Stat())->setAdd());
                    $this->numberOfDifferences++;
                }
            }

            $structures->next();
        }

        $structures->rewind();
        return ($structures);
    }

    /**
     * Getting the following is not an equal offer in the collection.
     * Equal records fall into the collection empty offers.
     *
     * @param Offers $offers The collection empty offers.
     * @param Offers $newOffers The collection new offers.
     * @param Offers $oldOffers The collection old offers.
     * @access private
     * @return bool
     */
    private function nextValidOffer(Offers $offers, Offers $newOffers, Offers $oldOffers)
    {
        if (!$newOffers->valid()) {
            $this->excluded = [];
            return (false);
        }

        while ($oldOffers->valid()) {

            $newOffer = $newOffers->current()->getText();
            $oldOffer = $oldOffers->current()->getText();

            if ($oldOffer === $newOffer) {

                // equal offer
                $offerEqual = new Offer();
                $offerEqual->setNew($newOffer)
                    ->setOld($oldOffer)
                    ->setStat((new Stat())->setEqual());

                $this->excluded[] = $newOffers->key();
                $oldOffers->offsetUnset($oldOffers->key());

                // remove offer duplicate
                if ($oldOffers->offsetExists($newOffers->key())) {
                    $offer = new Offer();
                    $offer->setOld($oldOffers->offsetGet($newOffers->key())->getText())
                        ->setStat((new Stat())->setRemove());

                    $offers->append($offer);
                    $this->numberOfDifferences++;
                }

                $offers->append($offerEqual);
                break;
            }

            $oldOffers->next();
        }
        $oldOffers->rewind();

        // recursive search
        if (in_array($newOffers->key(), $this->excluded)) {
            $newOffers->next();
            return ($this->nextValidOffer($offers, $newOffers, $oldOffers));
        }

        return ($newOffers->valid());
    }
}
