<?php

namespace Thsgroup\FeedParser\Validator\Constraint;


class Constraint
{
    private $errors;

    /**
     * Constraint constructor.
     */
    public function __construct()
    {
        $this->errors = new \ArrayObject(array());
    }

    /**
     * @return \ArrayObject
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param \ArrayObject $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * @param array $error
     */
    public function setError($error)
    {
        if (isset($error['code'])) {
            $this->errors[$error['code']] = isset($error['message']) ? $error['message'] : null;
        }
    }
}
