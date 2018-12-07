<?php

namespace Thsgroup\FeedParser\Validator;

use Thsgroup\FeedParser\Validator\Constraint\ConstraintInterface;
use Thsgroup\FeedParser\Validator\Constraint\ParametersInputFormatConstraint;
use Thsgroup\FeedParser\Validator\Constraint\ParametersOutputFormatConstraint;
use Thsgroup\FeedParser\Validator\Constraint\ParametersOutputPathConstraint;
use Thsgroup\FeedParser\Validator\Exception\ParametersRequiredException;

/**
 * Class ValidatorParameters
 * @package Thsgroup\FeedParser\Validator
 */
class ValidatorParameters implements ValidatorInterface
{
    private $errors = array();
    private static $requiredParameters = array(
        'formatInput',
        'formatOutput',
        'dirOutput'
    );

    /**
     * @param $data
     * @return bool
     * @throws ParametersRequiredException
     */
    public function validate($data)
    {
        if (!$this->checkRequiredParameters($data)) {
            return false;
        }

        if (is_array($data) && isset($data['formatInput']) && !$this->validateValue($data['formatInput'], new ParametersInputFormatConstraint())) {
            return false;
        }

        if (is_array($data) && isset($data['formatOutput']) && !$this->validateValue($data['formatOutput'], new ParametersOutputFormatConstraint())) {
            return false;
        }

        if (is_array($data) && isset($data['dirOutput']) && !$this->validateValue($data['dirOutput'], new ParametersOutputPathConstraint())) {
            return false;
        }

        return true;
    }

    public function validateValue($value, ConstraintInterface $constraint)
    {
        $valid = $constraint->check($value);
        $this->errors = $constraint->getErrors();

        return $valid;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    private function checkRequiredParameters($data)
    {
        $valid = count(array_diff(self::$requiredParameters, array_keys($data))) === 0;
        if (!$valid) {
            $this->errors['requiredParameters'] = 'Not all required parameters were provided, required: ' . implode(', ', self::$requiredParameters);
        }

        return $valid;
    }
}
