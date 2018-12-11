<?php

use PHPUnit\Framework\TestCase;

class ValidatorParametersTest extends TestCase
{

    /**
     * @var \Thsgroup\FeedParser\Validator\ValidatorParameters
     */
    protected $validatorParameters;

    protected function setUp()
    {
        $this->validatorParameters = new \Thsgroup\FeedParser\Validator\ValidatorParameters();
    }

    public function testIsThereAnySyntaxError()
    {
        $this->assertInternalType('object', $this->validatorParameters);
    }

    /**
     * @dataProvider dataProvider1
     * @param $data
     * @param $expected
     */
    public function testValidate($data, $expected)
    {
        $this->assertEquals($expected, $this->validatorParameters->validate($data));
    }

    /**
     * @dataProvider dataProvider2
     * @param $data
     * @param $expected
     */
    public function testValidateValue($data, $expected)
    {
        $this->assertEquals($expected, $this->validatorParameters->validateValue($data['value'], $data['constraint']));
    }

    public function testGetErrors()
    {
        $data = array();
        $this->validatorParameters->validate($data);

        $errors = $this->validatorParameters->getErrors();
        $this->assertArrayHasKey('requiredParameters', $errors);
    }



    public static function dataProvider1()
    {
        return array(
            array(
                array(
                    'formatInput' => 'rmv3',
                    'formatOutput' => 'adf',
                    'dirOutput' => 'data/'
                ),
                true
            ),
            array(
                array(
                    'formatInput' => 'a',
                    'formatOutput' => 'adf',
                    'dirOutput' => 'data/'
                ),
                false
            ),
            array(
                array(
                    'formatInput' => 'rmv3',
                    'formatOutput' => 'b',
                    'dirOutput' => 'data/'
                ),
                false
            ),
            array(
                array(
                    'formatInput' => 'rmv3',
                    'formatOutput' => 'adf',
                    'dirOutput' => ''
                ),
                false
            )
        );
    }

    public static function dataProvider2()
    {
        return array(
            array(
                array(
                    'constraint' => new \Thsgroup\FeedParser\Validator\Constraint\ParametersInputFormatConstraint(),
                    'value' => 'rmv3'
                ),
                true
            ),
            array(
                array(
                    'constraint' => new \Thsgroup\FeedParser\Validator\Constraint\ParametersInputFormatConstraint(),
                    'value' => 'invalid'
                ),
                false
            ),
            array(
                array(
                    'constraint' => new \Thsgroup\FeedParser\Validator\Constraint\ParametersOutputFormatConstraint(),
                    'value' => 'adf'
                ),
                true
            ),
            array(
                array(
                    'constraint' => new \Thsgroup\FeedParser\Validator\Constraint\ParametersOutputFormatConstraint(),
                    'value' => 'invalid'
                ),
                false
            ),
            array(
                array(
                    'constraint' => new \Thsgroup\FeedParser\Validator\Constraint\ParametersOutputPathConstraint(),
                    'value' => 'data/'
                ),
                true
            ),
            array(
                array(
                    'constraint' => new \Thsgroup\FeedParser\Validator\Constraint\ParametersOutputPathConstraint(),
                    'value' => false
                ),
                false
            )
        );
    }
}
