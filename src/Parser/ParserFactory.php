<?php

namespace Thsgroup\FeedParser\Parser;

class ParserFactory
{

    public static function create($type)
    {
        $parser = null;

        if ($type === 'rmv3') {
            $parser = new Rmv3Parser();
        }

        return $parser;
    }
}
