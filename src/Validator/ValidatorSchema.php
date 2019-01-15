<?php

namespace Thsgroup\FeedParser\Validator;

use Thsgroup\FeedParser\Validator\Constraint\ConstraintInterface;
use Thsgroup\FeedParser\Validator\Constraint\SchemaValidConstraint;

class ValidatorSchema implements ValidatorInterface
{
    private $parameters;
    private $errors = array();

    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }

    public function validate($response)
    {
        if ($this->parameters['validationSchema'] !== null && file_exists($this->parameters['validationSchema'])) {

            foreach ($response as $data) {

                if (!empty($data) && !$this->validateValue($data, new SchemaValidConstraint($this->parameters))) {
                    return false;
                }
            }
        }

        return true;
    }

    public function validateValue($data, ConstraintInterface $constraint)
    {
        $valid = $constraint->check($data);
        $this->errors = $constraint->getErrors();

        return $valid;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
