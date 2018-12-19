<?php

namespace Thsgroup\FeedParser\Parser;

interface ParserInterface
{
    public function setData($data);

    public function getData();

    public function process();
}
