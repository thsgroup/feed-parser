<?php

namespace Thsgroup\FeedParser\Validator\Constraint;

interface ConstraintInterface
{
    public function check($data);

    public function getErrors();
}
