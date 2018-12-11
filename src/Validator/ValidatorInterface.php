<?php

namespace Thsgroup\FeedParser\Validator;

use Thsgroup\FeedParser\Validator\Constraint\ConstraintInterface;

interface ValidatorInterface
{
    public function validate($data);

    public function validateValue($data, ConstraintInterface $constraint);

    public function getErrors();
}
