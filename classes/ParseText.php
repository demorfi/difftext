<?php

/**
 * DiffText - Parsing of text structure.
 * User: demorfi@gmail.com
 */
class ParseText
{

    /**
     * Collection structures.
     *
     * @var Structures
     * @access private
     */
    private $structures;

    /**
     * Initializes a new parsing on the structure of the text.
     *
     * @param Structures $structures Collection structures
     * @access public
     */
    public function __construct(Structures $structures)
    {
        $this->structures = $structures;
    }

    /**
     * Parsing text.
     *
     * @param Text $text Text object
     * @access public
     * @return void
     */
    public function doParse(Text $text)
    {
        $this->structures->switchToType($text);

        // filtering text on extra shifts
        $content = preg_replace(['/\x0D/', '/\x0A+/'], [null, chr('0x0A')], $text->getContent());

        // split text the new line
        $lines = new ArrayIterator(preg_split('/\x0A/', $content));
        while ($lines->valid()) {

            // split offers by points
            $offers = new ArrayIterator(
                preg_split(
                    '/(\s+\.+)|(\.+\s+)|(\?+\s+)|(\s+\?+)|(\!+\s+)|(\s+\!+)/',
                    $lines->current(),
                    null,
                    PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
                )
            );

            // compilation correct sentences
            while ($offers->valid()) {
                $key = $offers->key();

                if ($offers->offsetExists($key - 1)) {
                    if (preg_match('/^\s+(\.|\!|\?)+$/', $offers->current())) {
                        $offers->offsetSet($key - 1, $offers->offsetGet($key - 1) . $offers->current());
                    }

                    $match = [];
                    if (preg_match('/^(?P<sep>(\.|\!|\?)+)(?P<space>(\s+))$/', $offers->current(), $match)) {
                        $postfix = ($offers->offsetExists($key + 1) ? $match['sep'] : $offers->current());
                        $offers->offsetSet($key - 1, $offers->offsetGet($key - 1) . $postfix);

                        if ($offers->offsetExists($key + 1)) {
                            $offers->offsetSet($key + 1, $match['space'] . $offers->offsetGet($key + 1));
                        }
                    }
                }

                $offers->next();
            }

            $offersClean = preg_grep(
                '/^(\s+\.+|\.+\s+|\?+\s+|\s+\?+|\!+\s+|\s+\!+)/',
                $offers->getArrayCopy(),
                PREG_GREP_INVERT
            );

            $structure = new Structure(
                $lines->current(),
                new Offers(array_values($offersClean))
            );

            $this->structures->append($structure);
            $lines->next();
        }

        $this->structures->rewind();
    }
}