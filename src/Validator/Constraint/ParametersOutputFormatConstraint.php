<?php

namespace Thsgroup\FeedParser\Validator\Constraint;

class ParametersOutputFormatConstraint extends Constraint implements ConstraintInterface
{
    const FORMAT_ADF  = 'adf';
    const FORMAT_RMV3 = 'rmv3';

    private static $validFormatList = array(
        self::FORMAT_ADF,
        self::FORMAT_RMV3,
    );

    public function check($data)
    {
        if (!in_array($data, self::$validFormatList, true)) {
            $this->setError(
                array(
                    'code' => 'invalidOutputFormat',
                    'message' => 'Invalid output format [' . $data . '], currently available: ' . implode(', ', self::$validFormatList)
                ));
            return false;
        }

        return true;
    }
}
