<?php

namespace Thsgroup\FeedParser;

class DataOutputter
{
    private $directory;

    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    public function store($content, $filename)
    {
        file_put_contents($this->directory . DIRECTORY_SEPARATOR . $filename, $content);
    }
}
