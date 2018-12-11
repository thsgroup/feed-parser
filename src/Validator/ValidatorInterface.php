<?php

namespace Thsgroup\FeedParser\Validator;

interface ValidatorInterface
{
    public function validate($data);

    public function validateValue($data, $constraint);

    public function getErrors();
}
