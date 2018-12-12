<?php

namespace Thsgroup\FeedParser\Validator;

use Thsgroup\FeedParser\Validator\Constraint\ConstraintInterface;
use Thsgroup\FeedParser\Validator\Constraint\FileExistsConstraint;
use Thsgroup\FeedParser\Validator\Constraint\UrlExistsConstraint;

class ValidatorFiles implements ValidatorInterface
{
    private $errors = array();

    public function validate($data)
    {
        if (is_array($data)) {

            $multipleFilesValidity = array();

            foreach ($data as $file) {
                if ($this->isStringUrl($file)) {
                    $multipleFilesValidity[] = $this->validateValue($file, new UrlExistsConstraint()) ? 'true' : 'false';
                } else {
                    $multipleFilesValidity[] = $this->validateValue($file, new FileExistsConstraint()) ? 'true' : 'false';
                }
            }

            $multipleFilesValidityCount = array_count_values($multipleFilesValidity);
            return !isset($multipleFilesValidityCount['false']) ? true : false;
        }

        if (!empty($data) && $this->isStringUrl($data)) {
            return $this->validateValue($data, new UrlExistsConstraint());
        }

        if (!empty($data)) {
            return $this->validateValue($data, new FileExistsConstraint());
        }

        return false;
    }

    public function validateValue($value, ConstraintInterface $constraint)
    {
        $valid = $constraint->check($value);
        if (count($constraint->getErrors()) > 0) {
            $this->errors[] = $constraint->getErrors();
        }

        return $valid;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    public function isStringUrl($data)
    {
        if (filter_var($data, FILTER_VALIDATE_URL)) {
            return true;
        }

        return false;
    }
}
