<?php

namespace Thsgroup\FeedParser\Validator;

use Thsgroup\FeedParser\Validator\Exception\ParametersOutputPathException;

class ValidatorParametersOutputPath implements ValidatorInterface
{
    public function validate($data)
    {
        if (empty($data) || ctype_print($data) === false) {
            throw new ParametersOutputPathException('Invalid output path provided: ' . $data);
        }

        return true;
    }
}
