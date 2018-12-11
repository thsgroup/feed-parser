<?php

use PHPUnit\Framework\TestCase;

class DataRetrieverTest extends TestCase
{
    protected $testDirectory;

    protected function setUp()
    {
        $this->testDirectory = './temp';
    }

    protected function tearDown()
    {
        if (is_dir($this->testDirectory)) {
            rmdir($this->testDirectory);
        }
    }

    public function testIsValidInstance()
    {
        $retriever = new \Thsgroup\FeedParser\DataRetriever($this->testDirectory);
        $this->assertInstanceOf(\Thsgroup\FeedParser\DataRetriever::class, $retriever);
    }

    public function testPrepareDirectory()
    {
        $retriever = new \Thsgroup\FeedParser\DataRetriever($this->testDirectory);

        $this->assertEquals(true, $retriever->prepareDirectory());
        $this->assertFileExists($this->testDirectory);

        $retriever->removeDirectory($this->testDirectory);
    }

    public function testPrepareDirectoryException()
    {
        $directory = '';
        $this->expectException(RuntimeException::class);

        $retriever = new \Thsgroup\FeedParser\DataRetriever($directory);
        $retriever->prepareDirectory();
    }

    public function testRemoveDirectory()
    {
        $retriever = new \Thsgroup\FeedParser\DataRetriever($this->testDirectory);
        $this->assertEquals(true, $retriever->prepareDirectory());
        $this->assertFileExists($this->testDirectory);
        $retriever->removeDirectory($this->testDirectory);
        $this->assertFileNotExists($this->testDirectory);
    }

    public function testRemoveDirectoryRecursive()
    {
        $retriever = new \Thsgroup\FeedParser\DataRetriever($this->testDirectory . '/test');
        $this->assertEquals(true, $retriever->prepareDirectory());
        $this->assertFileExists($this->testDirectory);
        $retriever->removeDirectory($this->testDirectory);
        $this->assertFileNotExists($this->testDirectory);
    }
}
