<?php

use PHPUnit\Framework\TestCase;

class FeedParserClassTest extends TestCase
{
    public function testIsThereAnySyntaxError()
    {
        $var = new Thsgroup\FeedParser\FeedParserClass();
        $this->assertInternalType('object', $var);
        unset($var);
    }

    public function testInitialMethod()
    {
        $var = new Thsgroup\FeedParser\FeedParserClass();
        $this->assertEquals($var->initialMethod(), 'Test');
        unset($var);
    }
}
