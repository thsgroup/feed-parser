<?php

namespace Thsgroup\FeedParser\Validator;

class ValidatorParameters implements ValidatorInterface
{
    public function validate($data)
    {
        $validator = new ValidatorParametersInputFormat();
        $validator->validate($data['formatInput']);

        $validator = new ValidatorParametersOutputFormat();
        $validator->validate($data['formatOutput']);

        $validator = new ValidatorParametersOutputPath();
        $validator->validate($data['dirOutput']);

        return true;
    }
}
