<?php

use PHPUnit\Framework\TestCase;

class DataRetrieverTest extends TestCase
{
    protected $testDirectory;

    protected function setUp()
    {
        $this->testDirectory = 'temp';
    }

    protected function tearDown()
    {
        $this->removeDirectory($this->testDirectory);
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
        $retriever = new \Thsgroup\FeedParser\DataRetriever($this->testDirectory . DIRECTORY_SEPARATOR . 'test');
        $this->assertEquals(true, $retriever->prepareDirectory());
        $this->assertFileExists($this->testDirectory);
        $retriever->removeDirectory($this->testDirectory);
        $this->assertFileNotExists($this->testDirectory);
    }

    /**
     * @dataProvider dataProvider
     * @param $data
     * @param $expected
     */
    public function testStoreFiles($data, $expected)
    {
        $this->generateFiles($data);

        $retriever = new \Thsgroup\FeedParser\DataRetriever($this->testDirectory . DIRECTORY_SEPARATOR . 'out');
        $retriever->storeFiles($data);

        $this->assertCount($expected, $retriever->getStoredFiles());
    }

    /**
     * @dataProvider dataProvider2
     * @param $data
     * @param $expected
     */
    public function testStoreFilesException($data, $expected)
    {
        $this->expectException($expected);
        $this->generateFiles($data);

        $retriever = new \Thsgroup\FeedParser\DataRetriever($this->testDirectory . DIRECTORY_SEPARATOR . 'out');
        $retriever->storeFiles($data);
    }

    public function dataProvider()
    {
        return array(
            array(
                array(
                    'temp' . DIRECTORY_SEPARATOR . '1.txt',
                    'temp' . DIRECTORY_SEPARATOR . '2.txt',
                    'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js',
                ),
                3
            ),
            array(
                'temp' . DIRECTORY_SEPARATOR . '1.txt',
                1
            )
        );
    }

    public function dataProvider2()
    {
        return array(
            array(
                array(
                    'temp/1.txt',
                    'temp/2.txt',
                    'https://localhost-invalid/some-file.txt',
                ),
                RuntimeException::class
            ),
            array(
                false,
                RuntimeException::class
            )
        );
    }

    private function generateFiles($data)
    {
        $data = (!is_array($data)) ? array($data) : $data;
        $validatorFiles = new \Thsgroup\FeedParser\Validator\ValidatorFiles();

        if (!is_dir($this->testDirectory) && !mkdir($this->testDirectory, 0777, true)) {
            $this->fail('Could not create test directory');
        }

        foreach ($data as $file) {
            if (!empty($file) && !$validatorFiles->isStringUrl($file)) {
                $fileHandle = fopen($file, 'wb');
                fclose($fileHandle);
            }
        }
    }

    private function removeDirectory($path)
    {
        $files = glob($path . '/*');
        foreach ($files as $file) {
            is_dir($file) ? $this->removeDirectory($file) : unlink($file);
        }
        if (file_exists($path)) {
            rmdir($path);
        }
    }
}
