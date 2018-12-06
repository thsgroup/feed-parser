<?php

namespace Thsgroup\FeedParser\Validator;

use Thsgroup\FeedParser\Validator\Exception\ParametersOutputFormatException;

class ValidatorParametersOutputFormat implements ValidatorInterface
{
    const FORMAT_ADF = 'adf';

    private static $validFormatList = array(
        self::FORMAT_ADF,
    );

    public function validate($data)
    {
        if (!in_array($data, self::$validFormatList, true)) {
            throw new ParametersOutputFormatException('Invalid output format [' . $data . '], currently available: ' . implode(', ', self::$validFormatList));
        }

        return true;
    }
}
