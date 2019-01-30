<?php

namespace Thsgroup\FeedParser\Parser;

class ParserFactory
{

    /**
     * @param string $type
     * @param array $maps
     * @return Rmv3Parser|null
     */
    public static function create($type, $maps = array())
    {
        $parser = null;

        if ($type === 'rmv3') {
            $parser = new Rmv3Parser($maps);
        }

        return $parser;
    }
}
