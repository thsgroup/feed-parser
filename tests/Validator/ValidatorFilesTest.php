<?php

use PHPUnit\Framework\TestCase;
use Thsgroup\FeedParser\Validator\ValidatorFiles;
use Thsgroup\FeedParser\Validator\ValidatorInterface;

class ValidatorFilesTest extends TestCase
{

    /**
     * @var ValidatorFiles
     */
    protected $validatorFiles;
    protected $testFiles;
    protected $testDir;

    protected function setUp()
    {
        $this->validatorFiles = new ValidatorFiles();
        $this->testFiles = array(
            '1.txt',
            '2.txt',
            '3.txt'
        );
        $this->testDir = __DIR__ . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR;

        $this->generateFiles();
    }

    protected function tearDown()
    {
        foreach ($this->testFiles as $file) {
            if (file_exists($this->testDir . $file)) {
                unlink($this->testDir . $file);
            }
        }
        rmdir($this->testDir);
    }


    public function testIsValidInstance()
    {
        $this->assertInstanceOf(ValidatorInterface::class, $this->validatorFiles);
    }

    /**
     * @dataProvider dataProvider
     * @param $data
     * @param $expected
     */
    public function testValidate($data, $expected)
    {
        $this->assertEquals($expected, $this->validatorFiles->validate($data));
    }

    public function testGetErrors()
    {
        $data = 'invalid';
        $this->validatorFiles->validate($data);
        $errors = $this->validatorFiles->getErrors();
        $this->assertArrayHasKey('invalidInputFile', $errors[0]);

        $data = 'http://invalid-url';
        $this->validatorFiles->validate($data);
        $errors = $this->validatorFiles->getErrors();
        $this->assertArrayHasKey('invalidInputURL', $errors[1]);
    }

    private function generateFiles()
    {
        if (!is_dir($this->testDir) && !mkdir($this->testDir, 0777, true)) {
            $this->fail('Could not create test directory');
        }

        foreach ($this->testFiles as $file) {
            $fileHandle = fopen($this->testDir . $file, 'wb');
            fclose($fileHandle);
        }
    }

    public static function dataProvider()
    {
        return array(
            array(
                __DIR__ . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . '1.txt',
                true
            ),
            array(
                __DIR__ . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'invalid',
                false
            ),
            array(
                array(
                    __DIR__ . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . '1.txt',
                    __DIR__ . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . '2.txt',
                    __DIR__ . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . '3.txt',
                ),
                true
            ),
            array(
                array(
                    __DIR__ . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . '1.txt',
                    __DIR__ . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'invalid',
                    __DIR__ . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . '3.txt',
                ),
                false
            ),
            array(
                'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js',
                true
            ),
            array(
                array(
                    'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js',
                ),
                true
            ),
            array(
                array(
                    'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js',
                    ''
                ),
                false
            ),
            array(
                false,
                false
            )
        );
    }
}
