<?php

namespace Thsgroup\FeedParser\Validator;

use Thsgroup\FeedParser\Validator\Exception\ParametersInputFormatException;

class ValidatorParametersInputFormat implements ValidatorInterface
{
    const FORMAT_ADF  = 'adf';
    const FORMAT_RMV3 = 'rmv3';

    private static $validFormatList = [
        self::FORMAT_ADF,
        self::FORMAT_RMV3,
    ];

    public function validate($data)
    {
        if (!in_array($data, self::$validFormatList, true)) {
            throw new ParametersInputFormatException('Invalid input format [' . $data . '], currently available: ' . implode(', ', self::$validFormatList));
        }

        return true;
    }
}
