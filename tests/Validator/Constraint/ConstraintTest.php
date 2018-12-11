<?php

use PHPUnit\Framework\TestCase;

class ConstraintTest extends TestCase
{

    /**
     * @var \Thsgroup\FeedParser\Validator\Constraint\Constraint
     */
    protected $constraint;

    protected function setUp()
    {
        $this->constraint = new Thsgroup\FeedParser\Validator\Constraint\Constraint();
    }

    public function testIsValidInstance()
    {
        $this->assertInstanceOf(\Thsgroup\FeedParser\Validator\Constraint\Constraint::class, $this->constraint);
    }

    public function testSetAndGetErrors()
    {
        $this->constraint->setErrors(array('error' => true));
        $this->assertArrayHasKey('error', $this->constraint->getErrors());
    }
}
